<?php

namespace App\Http\Controllers;

use App\Models\ClassApp;
use App\Models\ClassTopic;
use App\Models\ScoreType;
use App\Models\ScoreSetting;
use App\Models\Student;
use App\Models\StudentScore;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassTopicMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClassApp $class)
    {
        $user = Auth::user();
        
        // Validasi akses mahasiswa
        // if (!$user->student || !$class->students->contains($user->student)) {
        //     return response()->json([
        //         'message' => 'Unauthorized: Not enrolled in this class'
        //     ], 403);
        // }

        // Ambil semua topik beserta menu-nya
        $topics = ClassTopic::with('classTopicMenu')
            ->where('class_id', $class->id)
            ->get();

        return response()->json([
            'class' => $class,
            'topics' => $topics->map(function($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'menus' => $topic->classTopicMenu->map(function($menu) {
                        return [
                            'id' => $menu->id,
                            'menu' => $menu->menu,
                            'is_modul' => $menu->is_modul,
                            'is_exam' => $menu->is_exam,
                            'created_at' => $menu->created_at->format('Y-m-d H:i:s')
                        ];
                    })
                ];
            })
        ]);
    }

    public function getExamQuestions(ClassTopic $classTopic, ScoreType $scoreType)
    {
        $user = Auth::user();
        
        $student = Student::where('user_id', $user->id)->first();
        $studentId = $student->id;
        
         // Ambil data ClassTopic untuk mendapatkan class_id
        $classTopic = ClassTopic::findOrFail($classTopic->id);
        $classId = $classTopic->class_id;

        // Cari ScoreSetting berdasarkan class_id dan score_type_id
        $scoreSetting = ScoreSetting::where('class_id', $classId)
            ->where('score_type_id', $scoreType->id)
            ->first();

        // Cek apakah ada record di StudentScore
        $scoreEntry = StudentScore::where('student_id', $studentId)
            ->where('class_topic_id', $classTopic->id)
            ->when($scoreSetting, function ($query) use ($scoreSetting) {
                $query->where('score_setting_id', $scoreSetting->id);
            })
            ->first();

        $examQuestions = $classTopic->examQuestions()
            ->get()->where('score_type_id', $scoreType->id)
            ->map(function ($question) {
                // Format data pivot
                return $question;
            })->makeHidden('true_answer');

        $responseData = [
            'is_answered' => $scoreEntry ? true : false,
            'score' => $scoreEntry ? $scoreEntry->score : null,
            'questions' => $examQuestions
        ];


        return response()->json([
            'data' => $responseData
        ], 200);
    }
}
