<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPresence extends Model
{
    protected $table = 'student_presence';
    /** @use HasFactory<\Database\Factories\StudentPresenceFactory> */
    use HasFactory;
    protected $fillable = ['class_topic_id', 'presence_type_id', 'student_id'];
    
    public function classTopic() {
        return $this->belongsTo(ClassTopic::class);
    }

    
    public function presenceType() {
        return $this->belongsTo(PresenceType::class);
    }

    
    public function student() {
        return $this->belongsTo(Student::class);
    }

}
