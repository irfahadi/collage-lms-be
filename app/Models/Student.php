<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'profile_picture', 'birthdate', 'phone_number', 'nim', 'user_id', 'study_program_id'];
    
    public function users() {
        return $this->belongsTo(Users::class);
    }

    
    public function studyProgram() {
        return $this->belongsTo(StudyProgram::class);
    }

    public function studentScore()
    {
        return $this->hasMany(StudentScore::class, 'student_id');
    }

    public function studentExamAnswer()
    {
        return $this->hasMany(StudentExamAnswer::class, 'student_id');
    }

    public function studentAssignment()
    {
        return $this->hasOne(StudentAssignment::class, 'student_id');
    }

    public function studentPresence()
    {
        return $this->hasMany(StudentPresence::class, 'student_id');
    }

    public function classes()
    {
        return $this->belongsToMany(
            ClassApp::class,    // Model yang di-relasikan
            'class_student',    // Nama pivot table
            'student_id',       // FK untuk model ini
            'class_id'          // FK untuk model relasi
        )->withTimestamps();
    }
}
