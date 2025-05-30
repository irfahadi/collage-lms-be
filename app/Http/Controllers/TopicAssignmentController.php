<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiOneResource;
use App\Models\TopicAssignment;
use App\Models\ClassTopicMenu;  
use Illuminate\Http\Request;

class TopicAssignmentController extends Controller
{
    /**
     * Cari assignment berdasar class_topic_id.
     * Untuk GET: lihat query param.
     * Untuk other: lihat body JSON.
     */
    protected function findByClassTopic(Request $request, bool $required = true)
    {
        $classTopicId = $request->isMethod('get')
            ? $request->query('topic_id')
            : $request->input('topic_id');

        if (!$classTopicId) {
            abort(400, 'class_topic_id wajib disertakan.');
        }

        $assignment = TopicAssignment::where('class_topic_id', $classTopicId)->first();
        // dd($assignment);

        if ($required && ! $assignment) {
            abort(404, "TopicAssignment untuk class_topic_id={$classTopicId} tidak ditemukan.");
        }

        return $assignment;
    }

    /**
     * GET /api/assignment?class_topic_id=…
     */
    public function index(Request $request)
    {
        $assignment = $this->findByClassTopic($request, false);
        return new ApiOneResource(200, 'Detail Tugas Dalam Topik', $assignment?$assignment:['class_topic_id' => $request->query('topic_id')]);
        
    }

    /**
     * POST /api/assignment
     * Body JSON: { class_topic_id, title, description? }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_topic_id' => 'required|integer|exists:class_topic,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'due_date'       => 'required|date_format:Y-m-d H:i:s',
        ]);
    
        // Cek apakah assignment sudah ada berdasarkan class_topic_id
        $assignment = TopicAssignment::where('class_topic_id', $data['class_topic_id'])->first();
    
        if ($assignment) {
            // Jika ada, update
            $assignment->update($data);
            $message = 'Tugas Dalam Topik Berhasil Diperbarui!';
        } else {
            // Jika belum ada, create
            ClassTopicMenu::create([
                'class_topic_id' => $data['class_topic_id'],
                'menu'           => 'Tugas',
                'is_exam'        => false,
                'is_modul'       => false
            ]);
    
            $assignment = TopicAssignment::create($data);
            $message = 'Tugas Dalam Topik Berhasil Dibuat!';
        }
    
        return new ApiOneResource(200, $message, $assignment);

    }

    /**
     * PUT /api/assignment
     * Body JSON: { class_topic_id, title?, description? }
     */
    public function update(Request $request)
    {
        $assignment = $this->findByClassTopic($request);

        $data = $request->validate([
            'class_topic_id' => 'required|integer|exists:class_topic,id',
            'title'          => 'sometimes|required|string|max:255',
            'description'    => 'sometimes|nullable|string',
            'due_date'       => 'required|date_format:Y-m-d H:i:s',
        ]);

        // (Optional) cek konsistensi class_topic_id di body vs record
        if ($data['class_topic_id'] != $assignment->class_topic_id) {
            abort(400, 'class_topic_id di body tidak sesuai dengan resource yang ada.');
        }

        $assignment->update(collect($data)->except('class_topic_id')->all());
        return new ApiResource(200, 'Tugas Dalam Topik Berhasil Dirubah!', $data);

    }

    /**
     * DELETE /api/assignment
     * Body JSON: { class_topic_id }
     */
    public function destroy(Request $request)
    {
        $assignment = $this->findByClassTopic($request);
        
        // ambil class_topic_id sebelum delete assignment
        $classTopicId = $assignment->class_topic_id;

        // 1) hapus assignment
        $assignment->delete();

        // 2) hapus menu "Tugas" untuk kelas ini
        ClassTopicMenu::where('class_topic_id', $classTopicId)
                      ->where('menu', 'Tugas')
                      ->delete();

        return new ApiResource(200, 'Tugas Dalam Topik Berhasil Dihapus!', [$request]);

    }
}