<?php

namespace App\Http\Controllers;

use App\Models\ClassApp;
use App\Models\ClassTopic;
use App\Models\StudentExamAnswer;
use App\Models\TopicExamQuestion;
use App\Models\Student;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentExamAnswerController extends Controller
{
    public function submitAnswers(Request $request, ClassTopic $classTopic)
    {
        // Validasi input
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.topic_exam_question_id' => 'required|integer|exists:topic_exam_questions,id',
            'answers.*.student_answer' => 'required|string|max:65535'
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        $studentId = $student->id;
        $examQuestionIds = collect($validated['answers'])->pluck('topic_exam_question_id');

        // Cek apakah ada jawaban yang sudah pernah di-submit
        $existingAnswers = StudentExamAnswer::where('student_id', $studentId)
            ->whereIn('topic_exam_question_id', $examQuestionIds)
            ->exists();

        if ($existingAnswers) {
            return response()->json([
                'message' => 'Anda sudah mengirimkan jawaban untuk pertanyaan ini sebelumnya'
            ], 422);
        }

        // Cek ketersediaan pertanyaan di class topic
        $validQuestionCount = DB::table('class_topic_modul_question')
            ->whereIn('topic_exam_question_id', $examQuestionIds)
            ->where('class_topic_id', $classTopic->id)
            ->count();

        if ($validQuestionCount != count($examQuestionIds)) {
            return response()->json([
                'message' => 'Terdapat pertanyaan yang tidak terkait dengan topik ini'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Simpan jawaban
            $answers = collect($validated['answers'])->map(function ($answerData) use ($studentId) {
                return StudentExamAnswer::create([
                    'student_id' => $studentId,
                    'topic_exam_question_id' => $answerData['topic_exam_question_id'],
                    'student_answer' => $answerData['student_answer']
                ]);
            });

            // Hitung skor
            $totalCorrect = 0;
            
            // Ambil pengaturan skor dari ClassApp
            $classApp = $classTopic->class; // Relasi ke ClassApp
            $firstQuestion = $answers->first()->topicExamQuestion;
            $scoreTypeId = $firstQuestion->score_type_id;
            $scoreSetting = $classApp->scoreSetting()
                ->where('score_type_id',$scoreTypeId)
                ->firstOrFail();
            // dd($scoreSetting);

            foreach ($answers as $answer) {
                $question = $answer->topicExamQuestion;
                if($question->is_essay){
                    $comparison = new \Atomescrochus\StringSimilarities\Compare();
                    $similar = $comparison->similarText($question->true_answer, $answer->student_answer); // Using "similar_text()"
                    if($similar>=70){
                        $totalCorrect++;
                    }
                }
                else if ($question->true_answer == $answer->student_answer) {
                    $totalCorrect++;
                }
            }

            // Simpan/Update skor
            $scoreData = [
                'student_id' => $studentId,
                'class_topic_id' => $classTopic->id,
                'score_setting_id' => $scoreSetting->id,
                'score' => $totalCorrect * 100 / $validQuestionCount
            ];

            StudentScore::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_topic_id' => $classTopic->id,
                    'score_setting_id' => $scoreSetting->id
                ],
                $scoreData
            );

            DB::commit();

            return response()->json([
                'message' => 'Jawaban dan skor berhasil disimpan',
                'data' => [
                    'answers' => $answers,
                    'score' => $scoreData['score']
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan jawaban',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
