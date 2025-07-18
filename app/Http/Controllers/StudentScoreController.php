<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentPresence;
use App\Models\StudentScore;
use App\Models\ScoreSetting;
use App\Models\ClassApp;
use App\Models\ClassTopic;
use Illuminate\Support\Facades\Auth;
use DB;

class StudentScoreController extends Controller
{
    /**
     * Get Student Scores for a specific class_id, class_topic_id, and score_setting_id
     * and calculate the median score per student.
     * 
     * @param  int  $class_id
     * @param  int  $class_topic_id
     * @param  int  $score_setting_id
     * @return \Illuminate\Http\Response
     */
    public function getStudentScores($class_id, $class_topic_id, $score_setting_id)
    {
        // Ambil semua student yang terdaftar dalam class_id tertentu
        $students = ClassStudent::where('class_id', $class_id)->pluck('student_id');

        // Menghitung median untuk setiap student berdasarkan class_topic_id dan score_setting_id
        $scores = StudentScore::whereIn('student_id', $students)
            ->where('class_topic_id', $class_topic_id)
            ->where('score_setting_id', $score_setting_id)
            ->select('student_id', 'score')
            ->get();

        // Group the scores by student_id and calculate the median
        $medianScores = $scores->groupBy('student_id')->map(function ($groupedScores) {
            $sortedScores = $groupedScores->pluck('score')->sort()->values()->toArray();
            $count = count($sortedScores);
            $middle = floor($count / 2);

            if ($count % 2 === 0) {
                // Jika jumlah skor genap, rata-rata dari dua nilai tengah
                $median = ($sortedScores[$middle - 1] + $sortedScores[$middle]) / 2;
            } else {
                // Jika jumlah skor ganjil, ambil nilai tengah
                $median = $sortedScores[$middle];
            }

            return ['median_score' => $median];
        });

        return response()->json([
            'class_id' => $class_id,
            'class_topic_id' => $class_topic_id,
            'score_setting_id' => $score_setting_id,
            'scores' => $medianScores
        ]);
    }

    public function report(Request $request){
        $request->validate(['class_id' => 'required|integer|exists:class,id']);
        $user=Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $classId = $request->query('class_id');
    
        // Validasi keanggotaan kelas
        $class = ClassApp::whereHas('students', fn($q) => $q->where('id', $student->id))
                         ->findOrFail($classId);

        $classTopicIds = ClassTopic::where('class_id', $classId)->pluck('id');
    
        $settings = ScoreSetting::where('class_id', $classId)
                      ->with('scoreType')
                      ->get();
    
        $scores = StudentScore::where('student_id', $student->id)
                   ->whereIn('score_setting_id', $settings->pluck('id'))
                   ->get();
    
        $groupedScores = $scores->groupBy('score_setting_id')
                          ->map(fn($group) => round($group->avg('score'), 2));
    
        // Proses pembuatan laporan
        $studentScores = $settings->map(function($setting) use ($groupedScores) {
            return [
                'score_type_id' => $setting->score_type_id,
                'score_type_name' => $setting->scoreType->type,
                'score_percent_value' => $setting->percent_value,
                'total_score' => $groupedScores->get($setting->id, 0),
            ];
        });
    
        // Hitung total skor dengan persentase
        $totalScore = $studentScores->sum(function($score) {
            return ($score['total_score'] * $score['score_percent_value']) / 100;
        });
    
        // Hitung jumlah pertemuan dan kehadiran
        $totalMeetings = ClassTopic::where('class_id', $classId)->count();

        $presenceData = StudentPresence::whereIn('class_topic_id', $classTopicIds)
            ->where('student_id', $student->id)
            ->get()
            ->groupBy('presence_type_id')
            ->map(fn($group) => $group->count());

        $defaultPresence = [
            1 => 0, // hadir
            2 => 0, // sakit
            3 => 0, // izin
            4 => 0, // alfa
        ];

        $presenceSummary = array_merge($defaultPresence, $presenceData->toArray());

        // Gabung data laporan
        $report = array_merge($student->toArray(),[
            'scores' => $studentScores,
            'total_score' => round($totalScore, 2),
            'attendance' => [
                'total_meetings' => $totalMeetings,
                'present' => $presenceSummary[1],
                'sick' => $presenceSummary[2],
                'permission' => $presenceSummary[3],
                'absent' => $presenceSummary[4],
            ],
        ]);
    
        return response()->json([
            'class_id' => $class->id,
            'data' => $report,
        ]);
    }
}