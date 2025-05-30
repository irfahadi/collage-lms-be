<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassApp extends Model
{
    protected $table = 'class';
    /** @use HasFactory<\Database\Factories\ClassAppFactory> */
    use HasFactory;
    protected $fillable = [
        'class_code', 
        'class_name_long', 
        'class_name_short', 
        'class_availability', 
        'visibility', 
        'description', 
        'class_thumbnail', 
        'tag', 
        'responsible_lecturer_id', 
        'study_program_id', 
        'period_id'
    ];
    
    public function lecturer() {
        return $this->belongsTo(Lecture::class, 'responsible_lecturer_id');
    }

    
    public function studyProgram() {
        return $this->belongsTo(StudyProgram::class);
    }

    
    public function period() {
        return $this->belongsTo(Period::class);
    }

    public function classTopic()
    {
        return $this->hasMany(ClassTopic::class, 'class_id');
    }

    public function scoreSetting()
    {
        return $this->hasMany(ScoreSetting::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,     // Model yang di-relasikan
            'class_student',    // Nama pivot table
            'class_id',         // FK untuk model ini
            'student_id'        // FK untuk model relasi
        )->withTimestamps();
    }
}
