<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentPresence;
use App\Models\StudentScore;
use App\Models\ScoreSetting;
use App\Models\ClassApp;

class StudentPresenceScoreController extends Controller
{
    /**
     * Get List of Student Presence for a specific class_id with class_topic_id as a query parameter
     * 
     * @param  int  $class_id
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentPresence($class_id, Request $request)
    {
        $class_topic_id = $request->query('class_topic_id');

        if (!$class_topic_id) {
            return response()->json(['error' => 'class_topic_id is required'], 400);
        }

        $class = ClassApp::with(['students'])->find($class_id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

            // Ambil semua student dari pivot, lalu map masing-masing dengan data presence jika ada
        $studentsWithPresence = $class->students->map(function ($student) use ($class_topic_id) {
            $presence = StudentPresence::where('student_id', $student->id)
                ->where('class_topic_id', $class_topic_id)
                ->with('presenceType')
                ->first();

            return [
                'student' => $student,
                'presence_type' => $presence ? $presence->presenceType : null,
                // 'presence_type_id' => $presence ? $presence->presence_type_id : null,
            ];
        });

        // Kembalikan response dengan student presence
        return response()->json([
            'class_id' => $class_id,
            'class_topic_id' => $class_topic_id,
            'data' => $studentsWithPresence
        ]);
    }
    /**
     * POST /api/class/{class}/student/presence
     *
     * Payload:
     * {
     *   "class_topic_id":    123,
     *   "student_id":        456,
     *   "presence_type_id":  1  // 1,2,3 → score 100; 4 → score 0
     * }
     */
    public function store(Request $request, $class_id)
    {
        $data = $request->validate([
            'class_topic_id'   => 'required|integer|exists:class_topic,id',
            'student_id'       => 'required|integer|exists:student,id',
            'presence_type_id' => 'required|integer|in:1,2,3,4',
        ]);

        // 1) Upsert presence
        $presence = StudentPresence::updateOrCreate(
            [
                'class_topic_id'   => $data['class_topic_id'],
                'student_id'       => $data['student_id'],
            ],
            ['presence_type_id' => $data['presence_type_id']]
        );

        // 2) Hitung score: 1|2|3 ⇒ 100, 4 ⇒ 0
        $scoreValue = in_array($data['presence_type_id'], [1,2,3]) ? 100 : 0;

        // 3) Ambil ScoreSetting untuk class_id + score_type_id = 1 (kehadiran)
        $scoreSetting = ScoreSetting::where([
            ['class_id',      $class_id],
            ['score_type_id', 1],
        ])->firstOrFail();

        // 4) Upsert student score
        $studentScore = StudentScore::updateOrCreate(
            [
                'class_topic_id'   => $data['class_topic_id'],
                'student_id'       => $data['student_id'],
                'score_setting_id'=> $scoreSetting->id,
            ],
            ['score' => $scoreValue]
        );

        return response()->json([
            'message'  => 'Presence and score saved.',
            'presence' => $presence,
            'score'    => $studentScore,
        ], 200);
    }
}