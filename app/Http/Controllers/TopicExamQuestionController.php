<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Models\TopicExamQuestion;
use App\Models\ClassTopicMenu;
use App\Models\ScoreType;
use App\Models\ClassTopic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TopicExamQuestionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validasi: class_topic_id wajib, score_type_id boleh kosong
        $v = Validator::make($request->all(), [
            'class_topic_id' => 'required|integer|exists:class_topic,id',
            'score_type_id'  => 'nullable|integer|exists:score_types,id',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Parameter tidak valid.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $classTopicId = $request->query('class_topic_id');
        $scoreTypeId  = $request->query('score_type_id');

        // 2. Jika score_type_id tidak disertakan, kembalikan array kosong
        if (is_null($scoreTypeId)) {
            return new ApiResource(200, 'List Pertanyaan Dalam Ujian Topik', []);
        }

        // 3. Ambil data questions
        $questions = TopicExamQuestion::where('class_topic_id', $classTopicId)
            ->where('score_type_id', $scoreTypeId)
            ->get([
                'id',
                'question',
                'is_essay',
                'is_multiple_choice',
                'multiple_choice_options',
                'true_answer'
            ]);

        return new ApiResource(200, 'List Pertanyaan Dalam Ujian Topik', $questions);
        
    }
    /**
     * Bulk sync TopicExamQuestion + pivot + menu
     *
     * Request JSON:
     * {
     *   "class_topic_id": 5,
     *   "score_type_id": 2,
     *   "questions": [
     *     {
     *       "id": 1,                          // nullable: update jika ada
     *       "question": "Pertanyaan ...",
     *       "is_essay": true,
     *       "is_multiple_choice": false,
     *       "multiple_choice_options": null,
     *       "true_answer": "Jawaban benar"
     *     },
     *     // …
     *   ]
     * }
     */
    public function bulkSync(Request $request)
    {
        // 1. Validasi
        $v = Validator::make($request->all(), [
            'class_topic_id'                => 'required|integer|exists:class_topic,id',
            'score_type_id'                 => 'required|integer|exists:score_types,id',
            'questions'                     => 'nullable|array',
            'questions.*.id'                => 'nullable|integer|exists:topic_exam_questions,id',
            'questions.*.question'          => 'required_with:questions|string',
            'questions.*.is_essay'          => 'required_with:questions|boolean',
            'questions.*.is_multiple_choice'=> 'required_with:questions|boolean',
            'questions.*.multiple_choice_options' => 'nullable|string',
            'questions.*.true_answer'       => 'required_with:questions|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Data tidak valid.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $classTopicId = $request->input('class_topic_id');
        $scoreTypeId  = $request->input('score_type_id');
        $items        = $request->input('questions', []);
        $processedIds = [];

        // Ambil field 'type' dari score_types
        // $scoreType = ScoreType::findOrFail($scoreTypeId);
        // dd($scoreType->type);

        DB::transaction(function() use ($items, $classTopicId, $scoreTypeId, &$processedIds) {
            // 2. Upsert setiap question
            foreach ($items as $attrs) {
                $data = [
                    'question'                => $attrs['question'],
                    'is_essay'                => $attrs['is_essay'],
                    'is_multiple_choice'      => $attrs['is_multiple_choice'],
                    'multiple_choice_options' => $attrs['multiple_choice_options'] ?? null,
                    'true_answer'             => $attrs['true_answer'],
                    'class_topic_id'          => $classTopicId,
                    'score_type_id'           => $scoreTypeId,
                ];

                if (! empty($attrs['id'])) {
                    $q = TopicExamQuestion::find($attrs['id']);
                    $q->update($data);
                } else {
                    $q = TopicExamQuestion::create($data);
                }

                $processedIds[] = $q->id;
            }

            // 3. Hapus question lama yang tidak ada di payload
            TopicExamQuestion::where('class_topic_id', $classTopicId)
                ->where('score_type_id', $scoreTypeId)
                ->when(count($processedIds) > 0, fn($q) => $q->whereNotIn('id', $processedIds))
                ->delete();

            // 4. Sinkronisasi pivot untuk exam questions
            //    pivot rows where topic_modul_id IS NULL
            $pivot = DB::table('class_topic_modul_question')
                       ->where('class_topic_id', $classTopicId)
                       ->whereNull('topic_modul_id');

            // 4a. Hapus pivot lama
            if (count($processedIds) > 0) {
                $pivot->whereNotIn('topic_exam_question_id', $processedIds)->delete();
            } else {
                // jika kosong, hapus semua
                $pivot->delete();
            }

            // 4b. Insert pivot baru yang belum ada
            $existingQIds = DB::table('class_topic_modul_question')
                              ->where('class_topic_id', $classTopicId)
                              ->whereNull('topic_modul_id')
                              ->pluck('topic_exam_question_id')
                              ->toArray();

            foreach ($processedIds as $qid) {
                if (! in_array($qid, $existingQIds, true)) {
                    DB::table('class_topic_modul_question')->insert([
                        'class_topic_id'          => $classTopicId,
                        'topic_modul_id'          => null,
                        'topic_exam_question_id'  => $qid,
                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ]);
                }
            }

            // 5. Manage ClassTopicMenu.is_exam
            $hasQs = count($processedIds) > 0;
            if ($hasQs) {
                $menuLabel = $scoreTypeId == '3'
                ? 'Kuis'
                : ($scoreTypeId == '4'
                    ? 'UTS'
                    : 'UAS');
                ClassTopicMenu::firstOrCreate(
                    [
                        'class_topic_id' => $classTopicId,
                        'menu' => $menuLabel,
                        'is_modul'       => false,
                        'is_exam'        => true,
                    ]
                );
            } else {
                ClassTopicMenu::where('class_topic_id', $classTopicId)
                              ->where('is_exam', true)
                              ->delete();
            }
        });

        // 6. Kembalikan data akhir
        $result = TopicExamQuestion::whereIn('id', $processedIds)->get();

        return response()->json([
            'message' => 'TopicExamQuestion, pivot & menu berhasil disinkronkan.',
            'data'    => $result,
        ], 200);
    }
    public function student(Request $request)
    {
        // 1. Validasi parameter
        $v = Validator::make($request->all(), [
            'class_topic_id' => 'required|integer|exists:class_topic,id',
            'score_type_id'  => 'nullable|integer|exists:score_types,id',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Parameter tidak valid.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $classTopicId = $request->query('class_topic_id');
        $scoreTypeId  = $request->query('score_type_id');
        $studentId    = auth()->id();

        // Jika tidak ada score_type_id, kembalikan array kosong
        if (is_null($scoreTypeId)) {
            return new ApiResource(200, 'List Pertanyaan Dalam Ujian Topik', []);
        }

        // Ambil data ClassTopic untuk mendapatkan class_id
        $classTopic = ClassTopic::findOrFail($classTopicId);
        $classId = $classTopic->class_id;

        // Cari ScoreSetting berdasarkan class_id dan score_type_id
        $scoreSetting = ScoreSetting::where('class_id', $classId)
            ->where('score_type_id', $scoreTypeId)
            ->first();

        // Cek apakah ada record di StudentScore
        $scoreEntry = StudentScore::where('student_id', $studentId)
            ->where('class_topic_id', $classTopicId)
            ->when($scoreSetting, function ($query) use ($scoreSetting) {
                $query->where('score_setting_id', $scoreSetting->id);
            })
            ->first();

        // Ambil semua pertanyaan
        $questions = TopicExamQuestion::where('class_topic_id', $classTopicId)
            ->where('score_type_id', $scoreTypeId)
            ->get([
                'id',
                'question',
                'is_essay',
                'is_multiple_choice',
                'multiple_choice_options',
            ]);

        // Format response sesuai permintaan
        $responseData = [
            'is_answered' => $scoreEntry ? true : false,
            'score' => $scoreEntry ? $scoreEntry->score : null,
            'questions' => $questions
        ];

        return new ApiResource(200, 'List Pertanyaan Dalam Ujian Topik', $responseData);
    }
}