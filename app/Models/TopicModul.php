<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicModul extends Model
{
    protected $table = 'topic_modules';
    /** @use HasFactory<\Database\Factories\TopicModulFactory> */
    use HasFactory;
    protected $fillable = ['modul', 'content', 'description', 'modul_type_id', 'class_topic_id'];
    
    public function modulType() {
        return $this->belongsTo(ModulType::class);
    }
    
    /**
     * Relasi ke ClassTopic via pivot.
     * Hanya akan join pivot dengan topic_modul_id != null.
     */
    public function classTopics()
    {
        return $this->belongsToMany(
            ClassTopic::class,
            'class_topic_modul_question',
            'topic_modul_id',
            'class_topic_id'
        )
        ->withPivot('topic_exam_question_id')
        ->withTimestamps();
    }


}
