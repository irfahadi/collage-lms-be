<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    /** @use HasFactory<\Database\Factories\FacultyFactory> */
    use HasFactory;
    
      // Menentukan nama tabel yang digunakan
    protected $table = 'faculty';

    // Kolom-kolom yang bisa diisi secara massal
    protected $fillable = [
        'name',
        'code',
        'description',
        'established_year',
        'dean_name',
        'contact_email',
        'contact_phone',
    ];

    /**
     * Relasi ke Study Program
     * Satu faculty memiliki banyak study program.
     */
    public function studyProgram()
    {
        return $this->hasMany(StudyProgram::class, 'faculty_id');
    }
}
