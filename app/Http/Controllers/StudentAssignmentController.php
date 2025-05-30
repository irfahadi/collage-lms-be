<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentAssignment;
use App\Models\TopicAssignment;
use App\Models\StudentScore;
use App\Models\ScoreSetting;
use App\Models\ClassApp;
use App\Models\ClassTopic;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentController extends Controller
{
    public function getStudentAssignments(Request $request, $class_id)
    {
        $validated = $request->validate([
            'topic_id' => 'required|integer',
        ]);

        // Find class dengan relasi students, studentAssignment, dan studentScores (dengan kondisi score_type = 2)
        $classApp = ClassApp::with([
            'students' => function ($query) use ($validated) {
                // Filter studentAssignment berdasarkan topic_id
                $query->with(['studentAssignment' => function ($q) use ($validated) {
                    $q->where('class_topic_id', $validated['topic_id']);
                }]);
            },
            // Eager load studentScores dengan kondisi score_type = 2 melalui scoreSetting
            'scoreSetting' => function ($query) {
                $query->where('score_type_id', 2); // ✅ Filter berdasarkan score_type dari ScoreSetting
            }
        ])->find($class_id);

        $scoreSettingId = $classApp->scoreSetting->first()->id;
        $studentScore = StudentScore::where('class_topic_id', $validated['topic_id'])->where('score_setting_id', $scoreSettingId); 

        if (!$classApp) {
            return response()->json(['message' => 'Class not found'], 404);
        }

       // Ambil data dari kedua query
        $students = $classApp->students;
        $scores = $studentScore->get();

        // Buat mapping student_id => score
        $scoresMap = [];
        foreach ($scores as $score) {
            $scoresMap[$score->student_id] = $score;
        }

        // Gabungkan data
        $result = $students->map(function ($student) use ($scoresMap) {
            // Salin data siswa
            $studentData = $student->toArray();

            if (!empty($studentData['student_assignment']['assignment_file'])) {
                // asumsikan content = path di bucket IdCloudHost
                $studentData['student_assignment']['assignment_file'] = Storage::disk('idcloud')->temporaryUrl(
                    $studentData['student_assignment']['assignment_file'],
                    now()->addMinutes(60)
                );
            }
            
            // Tambahkan data skor jika ada
            if (isset($scoresMap[$student->id])) {
                $studentData['total_score'] = $scoresMap[$student->id]->score;
            } else {
                $studentData['total_score'] = 0; // Atau nilai default
            }

            return $studentData;
        });

        return response()->json([
            'message' => 'Student assignments retrieved successfully',
            'data' => $result,
        ]);
    }

    public function upsertScore(Request $request, $class_id)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'class_topic_id' => 'required|integer',
            'score' => 'required|numeric|min:0|max:100',
            'lecture_feedback' => 'nullable|string'
        ]);

        // Cari score_setting_id berdasarkan score_type_id dan class_id
        $scoreSetting = ScoreSetting::where('score_type_id', 2)
                                    ->where('class_id', $class_id)
                                    ->first();

        if (!$scoreSetting) {
            return response()->json([
                'message' => 'Score setting not found for the given score_type_id and class_id'
            ], 404);
        }

        if(isset($validated['lecture_feedback'])){
            $studentAssignment = StudentAssignment::where('student_id', $validated['student_id'])->where('class_topic_id',$validated['class_topic_id'])->first();
            $studentAssignment->update([
                'lecture_feedback' => $validated['lecture_feedback']
            ]);
        }

        $existing = StudentScore::where('student_id', $validated['student_id'])->first();

        if ($existing) {
            $existing->update([
                'student_id' => $validated['student_id'],
                'score_setting_id' => $scoreSetting->id,
                'class_topic_id' => $validated['class_topic_id'],
                'score' => $validated['score'],
            ]);
            $studentScore = $existing;
        } else {
            $studentScore = StudentScore::create(
                [
                    'student_id' => $validated['student_id'],
                    'score_setting_id' => $scoreSetting->id,
                    'class_topic_id' => $validated['class_topic_id'],
                    'score' => $validated['score'],
                ]
            );
        }

        return response()->json([
            'message' => 'Student score inserted or updated successfully',
            'data' => $studentScore,
        ], 200);
    }
    
    public function submitAssignment(Request $request, ClassTopic $classTopic)
    {
        $validated = $request->validate([
            'assignment_file' => 'required|string',
            'lecture_feedback' => 'nullable|string',
            'revision_date' => 'nullable|date',
            'topic_assignment_id' => 'required|integer',
        ]);

         // Ambil student_id dari user yang login
        $validated['student_id'] = auth()->user()->student->id;
        $validated['class_topic_id'] = $classTopic->id; // Ambil dari model yang di-resolve

        // Ambil topic assignment terkait
        $topicAssignment = TopicAssignment::find($validated['topic_assignment_id']);
        if (!$topicAssignment) {
            return response()->json([
                'message' => 'Topic assignment not found'
            ], 404);
        }

        $now = now();

        // Validasi created_at dan updated_at harus sebelum due_date
        if ($now->gte($topicAssignment->due_date)) {
            throw ValidationException::withMessages([
                'due_date' => 'Current date/time must be before the due date of the assignment.'
            ]);
        }

        // Cari berdasarkan student_id dan class_topic_id
        $existing = StudentAssignment::where([
            'student_id' => $validated['student_id'],
            'class_topic_id' => $validated['class_topic_id']
        ])->first();

        if ($existing) {
            $existing->update($validated);
            $studentAssignment = $existing;
        } else {
            $studentAssignment = StudentAssignment::create($validated);
        }

        return response()->json([
            'message' => 'Student assignment record inserted or updated successfully',
            'data' => $studentAssignment,
        ], 200);
    }
}