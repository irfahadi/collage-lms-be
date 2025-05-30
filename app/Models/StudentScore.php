<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    protected $table = 'student_scores';
    //
    protected $fillable = ['score', 'student_id', 'score_setting_id', 'class_topic_id'];
    
    public function student() {
        return $this->belongsTo(Student::class);
    }

    
    public function scoreSetting() {
        return $this->belongsTo(ScoreSetting::class, 'score_setting_id');
    }

    
    public function classTopic() {
        return $this->belongsTo(ClassTopic::class);
    }

}
