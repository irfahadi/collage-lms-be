<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $table = 'lecture';
    /** @use HasFactory<\Database\Factories\LectureFactory> */
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'profile_picture', 'birthdate', 'phone_number', 'nidn', 'user_id', 'study_program_id'];
    
    public function users() {
        return $this->belongsTo(Users::class);
    }
    
    public function studyProgram() {
        return $this->belongsTo(StudyProgram::class);
    }

    public function classApp()
    {
        return $this->hasMany(ClassApp::class, 'class_id');
    }
}
