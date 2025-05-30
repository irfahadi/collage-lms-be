<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    protected $table = 'student_assignment';
    /** @use HasFactory<\Database\Factories\StudentAssignmentFactory> */
    use HasFactory;
    protected $fillable = ['assignment_file', 'lecture_feedback', 'revision_date', 'topic_assignment_id', 'class_topic_id', 'student_id'];
    
    public function topicassignment() {
        return $this->belongsTo(TopicAssignment::class);
    }

    
    public function classtopic() {
        return $this->belongsTo(ClassTopic::class);
    }

    
    public function student() {
        return $this->belongsTo(Student::class);
    }

}
