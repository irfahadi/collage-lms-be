<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\ClassApp;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\ScoreSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ClassStudentController extends Controller
{
    public function students(Request $request)
    {
        $studyProgramId = $request->query('study_program_id');
        $classId        = $request->query('class_id');

        $query = Student::latest();

        // filter by program studi jika ada
        if ($studyProgramId) {
            $query->where('study_program_id', $studyProgramId);
        }

        // exclude yang sudah ada di pivot untuk class tertentu
        if ($classId) {
            $query->whereDoesntHave('classes', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $students = $query->get();

        return new ApiResource(200, 'List Data Mahasiswa', $students);
    }
    /**
     * Read all students in a class.
     */
    public function index(ClassApp $class)
    {
        // eager load if perlu, misal: ->with('profile')
        $students = $class->students()->get();

        return new ApiResource(200, 'List Data Mahasiswa Dalam Kelas', $students);
    }
   /**
     * Attach multiple existing students (by ID) to the class.
     *
     * Payload:
     * {
     *   "student_ids": [1, 2, 3, ...]
     * }
     */
    public function store(Request $request, ClassApp $class)
    {
        // Validasi: harus array, minimal 1 id, dan setiap id harus ada di tabel students
        $payload = $request->validate([
            'student_ids'   => ['required', 'array', 'min:1'],
            'student_ids.*' => ['integer', 'distinct', 'exists:student,id'],
        ]);

        // Attach tanpa meng-detach yang sudah ada
        $class->students()->syncWithoutDetaching($payload['student_ids']);

        // Ambil data lengkap siswa yang baru di-attach
        $attached = $class
            ->students()
            ->whereIn('id', $payload['student_ids'])
            ->get();
        // dd($attached);

        return new ApiResource(200, 'List Data Mahasiswa Dalam Kelas Berhasil Ditambah!', $attached);

    }
    /**
     * Delete (detach) a student from the class.
     * Jika ingin juga menghapus record student, bisa toggle $forceDelete.
     */
    public function destroy(ClassApp $class, Student $student)
    {
        // detach from this class
        $class->students()->detach($student->id);

        // jika ingin hapus seluruh record student:
        // $student->delete();

        return new ApiResource(200, 'Data Mahasiswa Dalam Kelas Berhasil Dihapus!', ['id'=>$student->id]);

    }
    public function report($classId){
        $class = ClassApp::with('students')->findOrFail($classId);
        $students = $class->students;
    
        // Ambil semua ScoreSetting & ScoreType untuk kelas ini
        $settings = ScoreSetting::where('class_id', $classId)
                      ->with('scoreType')
                      ->get();
    
        // Ambil semua StudentScore untuk semua score_setting di kelas ini (bisa banyak topic)
        $scores = StudentScore::whereIn('score_setting_id', $settings->pluck('id'))
                   ->get();
    
        // Kelompokkan per student_id + score_setting_id lalu hitung total skor-nya
        $groupedScores = $scores->groupBy(function($item) {
            return $item->student_id . '-' . $item->score_setting_id;
        })->map(function($group) {
            return round($group->avg('score'), 2); // rata-rata dan dibulatkan 2 desimal
        });
    
        // Buat report looping nama siswa dulu
        $report = $students->map(function($student) use ($settings, $groupedScores) {
            $studentScores = $settings->map(function($setting) use ($student, $groupedScores) {
                $key = $student->id . '-' . $setting->id;
                return [
                    'score_type_id'   => $setting->score_type_id,
                    'score_type_name' => $setting->scoreType->type,
                    'score_percent_value' => $setting->percent_value,
                    'total_score'     => $groupedScores->get($key, 0), // default 0 kalau gak ada
                ];
            });
    
            return array_merge($student->toArray(),[
                'scores'       => $studentScores,
            ]);
        });
    
        return response()->json([
            'class_id' => $class->id,
            'data'   => $report,
        ]);

}
}