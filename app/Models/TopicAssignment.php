<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicAssignment extends Model
{
    protected $table = 'topic_assignment';
    /** @use HasFactory<\Database\Factories\TopicAssignmentFactory> */
    use HasFactory;
    
    protected $fillable = ['title', 'description', 'class_topic_id', 'due_date'];

    protected $casts = [
        'due_date' => 'datetime',
    ];
    
    public function classtopic() {
        return $this->belongsTo(ClassTopic::class);
    }

    public function studentAssignment()
    {
        return $this->hasMany(StudentAssignment::class, 'topic_assignment_id');
    }

}
