<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTopic extends Model
{
    protected $table = 'class_topic';
    /** @use HasFactory<\Database\Factories\ClassTopicFactory> */
    use HasFactory;

    protected $fillable = ['title', 'class_id'];
    
    public function class() {
        return $this->belongsTo(ClassApp::class);
    }

    public function classTopicMenu()
    {
        return $this->hasMany(ClassTopicMenu::class, 'class_topic_id');
    }

    public function topicAssignment()
    {
        return $this->hasOne(TopicAssignment::class, 'class_topic_id');
    }

    public function studentPresence()
    {
        return $this->hasMany(StudentPresence::class, 'class_topic_id');
    }

    public function studentScore()
    {
        return $this->hasMany(StudentScore::class, 'class_topic_id');
    }

     /**
     * Relasi ke TopicModul melalui pivot.
     * Hanya akan join pivot dengan topic_modul_id != null.
     */
    public function modules()
    {
        return $this->belongsToMany(
            TopicModul::class,
            'class_topic_modul_question', // nama pivot
            'class_topic_id',
            'topic_modul_id'
        )
        ->withPivot('topic_exam_question_id')
        ->withTimestamps();
    }

    /**
     * Relasi ke TopicExamQuestion melalui pivot.
     * Hanya akan join pivot dengan topic_exam_question_id != null.
     */
    public function examQuestions()
    {
        return $this->belongsToMany(
            TopicExamQuestion::class,
            'class_topic_modul_question',
            'class_topic_id',
            'topic_exam_question_id'
        )
        ->withPivot('topic_modul_id')
        ->withTimestamps();
    }
}
