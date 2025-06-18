<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Models\ClassTopic;
use App\Models\TopicModul;
use App\Models\ClassTopicMenu;  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TopicModulController extends Controller
{
    /**
     * Bulk upsert TopicModul & sync pivot (class_topic_modul_question)
     * hanya berdasarkan topic_modul_id <-> class_topic_id.
     *
     * Request JSON:
     * {
     *   "class_topic_id": 5,
     *   "modules": [
     *     {
     *       "id": 1,                   // nullable: update jika ada
     *       "modul": "Judul",
     *       "content": "Isi …",
     *       "description": "...",
     *       "modul_type_id": 2
     *     },
     *   ]
     * }
     */
    public function bulkSyncModules(Request $request)
    {
        // 1. Validasi (boleh kosong, tapi harus array)
        $v = Validator::make($request->all(), [
            'class_topic_id'           => 'required|integer|exists:class_topic,id',
            'modules'                  => 'nullable|array', // hapus min:1
            'modules.*.id'             => 'nullable|integer|exists:topic_modules,id',
            'modules.*.modul'          => 'required_with:modules.*.id|string|max:255',
            'modules.*.content'        => 'required_with:modules.*.id|string',
            'modules.*.description'    => 'nullable|string',
            'modules.*.modul_type_id'  => 'required_with:modules.*.id|integer|exists:modul_types,id',
        ]);
        if ($v->fails()) {
            return response()->json([
                'message' => 'Data tidak valid',
                'errors'  => $v->errors(),
            ], 422);
        }

        $classTopicId = $request->input('class_topic_id');
        $items        = $request->input('modules');
        $processedIds = [];

        DB::transaction(function() use ($classTopicId, $items, &$processedIds) {
            // 2. Upsert setiap modul
            foreach ($items as $attrs) {
                $data = [
                    'modul'          => $attrs['modul'],
                    'content'        => $attrs['content'],
                    'description'    => $attrs['description'] ?? null,
                    'modul_type_id'  => $attrs['modul_type_id'],
                    'class_topic_id' => $classTopicId, // tetap set untuk kemudahan delete
                ];

                if (! empty($attrs['id'])) {
                    $modul = TopicModul::find($attrs['id']);
                    $modul->update($data);
                } else {
                    $modul = TopicModul::create($data);
                }

                $processedIds[] = $modul->id;
            }

            // 3. Hapus modul lama yang tidak ada di payload
            TopicModul::where('class_topic_id', $classTopicId)
                ->whereNotIn('id', $processedIds)
                ->delete();

            // 4. Sinkronisasi pivot: class_topic_modul_question
            $classTopic = ClassTopic::find($classTopicId);
            $classTopic->modules()->sync($processedIds);

            // 5. Manage ClassTopicMenu.is_modul berdasarkan pivot
            $hasModules = count($processedIds) > 0;

            if ($hasModules) {
                // jika belum ada menu modul, buat baru
                ClassTopicMenu::firstOrCreate(
                    [
                        'class_topic_id' => $classTopicId,
                        'menu' => 'Modul',
                        'is_modul'       => true,
                        'is_exam'       => false,
                    ],
                );
            } else {
                // jika tidak ada modul sama sekali, hapus menu modul lama
                ClassTopicMenu::where('class_topic_id', $classTopicId)
                              ->where('is_modul', true)
                              ->delete();
            }
        });

        // 6. Ambil kembali data modul yang sudah diproses
        $result = TopicModul::whereIn('id', $processedIds)->get();

        return response()->json([
            'message' => 'Modul Dalam Topik Berhasil Diupdate',
            'data'    => $result,
        ], 200);
    }
    /**
     * Tampilkan semua TopicModul untuk suatu ClassTopic lewat query param,
     * dan buat presigned URL untuk modul_type_id = 1.
     *
     * Contoh: GET /api/class-topics/modules?class_topic_id=5
     */
    public function getModules(Request $request)
    {
        // 1. Validasi query parameter
        $v = Validator::make($request->all(), [
            'class_topic_id' => 'required|integer|exists:class_topic,id',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Parameter tidak valid.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $classTopicId = $request->query('class_topic_id');

        // 2. Ambil ClassTopic, lalu modules-nya
        $classTopic = ClassTopic::findOrFail($classTopicId);
        $modules    = $classTopic->modules()->get();

        // 3. Bangun response: presigned URL jika modul_type_id = 1
        $result = $modules->map(function($modul) {
            $data = [
                'id'            => $modul->id,
                'modul'         => $modul->modul,
                'description'   => $modul->description,
                'content'         => $modul->content,
                'modul_type_id' => $modul->modul_type_id,
            ];

            if ($modul->modul_type_id === 1) {
                // asumsikan content = path di bucket IdCloudHost
                $data['url'] = Storage::disk('idcloud')->temporaryUrl(
                    $modul->content,
                    now()->addMinutes(60),
                    [
                        'ResponseContentDisposition' => 'inline',
                        'ResponseContentType' => 'application/pdf',
                    ]   
                );
            } else {
                $data['content'] = $modul->content;
            }

            return $data;
        });

        return new ApiResource(200, 'List Data Modul Dalam Topik', $result);
    }
}