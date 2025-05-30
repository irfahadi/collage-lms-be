<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreType extends Model
{
    protected $table = 'score_types';
    /** @use HasFactory<\Database\Factories\ScoreTypeFactory> */
    use HasFactory;
    protected $fillable = ['type', 'is_exam'];

    public function scoreSetting()
    {
        return $this->hasMany(ScoreSetting::class, 'score_type_id');
    }

    public function topicExamQuestion()
    {
        return $this->hasMany(TopicExamQuestion::class, 'score_type_id');
    }
}
