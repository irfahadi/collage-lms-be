<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    /** @use HasFactory<\Database\Factories\StudyProgramFactory> */
    use HasFactory;
    
    // Menentukan nama tabel yang digunakan
    protected $table = 'study_program';

    // Kolom-kolom yang bisa diisi secara massal
    protected $fillable = [
        'name',
        'code',
        'description',
        'head_of_program',
        'established_year',
        'contact_email',
        'contact_phone',
        'faculty_id',
    ];

    /**
     * Relasi ke Faculty
     * Satu study program selalu dimiliki oleh satu faculty.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function lecture()
    {
        return $this->hasMany(Lecture::class, 'study_program_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class, 'study_program_id');
    }
}
