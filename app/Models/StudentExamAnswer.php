<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamAnswer extends Model
{
    protected $table = 'student_exam_answer';
    /** @use HasFactory<\Database\Factories\StudentExamAnswerFactory> */
    use HasFactory;
    protected $fillable = ['student_answer', 'topic_exam_question_id', 'student_id'];
    
    public function topicExamQuestion() {
        return $this->belongsTo(TopicExamQuestion::class);
    }

    
    public function student() {
        return $this->belongsTo(Student::class);
    }

}
