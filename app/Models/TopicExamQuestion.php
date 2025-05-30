<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicExamQuestion extends Model
{
    protected $table = 'topic_exam_questions';
    /** @use HasFactory<\Database\Factories\TopicExamQuestionFactory> */
    use HasFactory;
    protected $fillable = ['question', 'is_essay', 'is_multiple_choice', 'multiple_choice_options', 'true_answer', 'class_topic_id', 'score_type_id'];

    public function studentExamanswer()
    {
        return $this->hasMany(StudentExamAnswer::class, 'topic_exam_question_id');
    }

    public function scoreType() {
        return $this->belongsTo(ScoreType::class);
    }

    /**
     * Relasi ke ClassTopic via pivot.
     * Hanya akan join pivot dengan topic_exam_question_id != null.
     */
    public function classTopics()
    {
        return $this->belongsToMany(
            ClassTopic::class,
            'class_topic_modul_question',
            'topic_exam_question_id',
            'class_topic_id'
        )
        ->withPivot('topic_modul_id')
        ->withTimestamps();
    }
}
