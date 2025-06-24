<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassApp;
use App\Models\ClassTopic;
use App\Models\Lecture;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $content = [];
        // $content = ['message' => 'Hello, World!'];

        if ($user->role_id === 2) {
            $lecture = Lecture::where('user_id',$user->id)->with('studyProgram')->first();
            $facultyId = $lecture->studyProgram->faculty_id;
            $content['student_count'] = Student::whereHas('studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->count();

            $content['lecturer_count'] = Lecture::whereHas('studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->count();

            $content['class_count'] = ClassApp::whereHas('studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->count();

            // Untuk ClassTopic, perlu join melalui ClassApp → StudyProgram
            $content['meeting_count'] = ClassTopic::whereHas('class.studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->count();
            // Ambil list data dengan format yang diminta
            $content['students'] = Student::whereHas('studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->select('first_name', 'last_name')
            ->get()
            ->map(function ($student) {
                return [
                    'label' => $student->first_name . ' ' . $student->last_name,
                ];
            });

            $content['lecturers'] = Lecture::whereHas('studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->select('first_name', 'last_name')
            ->get()
            ->map(function ($lecture) {
                return [
                    'label' => $lecture->first_name . ' ' . $lecture->last_name,
                ];
            });

            $content['classes'] = ClassApp::whereHas('studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->select('class_name_long')
            ->get()
            ->map(function ($class) {
                return [
                    'label' => $class->class_name_long,
                ];
            });

            $content['meetings'] = ClassTopic::whereHas('class.studyProgram', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })->select('title')
            ->get()
            ->map(function ($topic) {
                return [
                    'label' => $topic->title,
                ];
            });
        } 

        if ($user->role_id === 5) {
            $lecture = Lecture::where('user_id',$user->id)->first();
            $studyProgramId=$lecture->study_program_id;
            $content['student_count'] = Student::where('study_program_id', $studyProgramId)->count();
            $content['lecturer_count'] = Lecture::where('study_program_id', $studyProgramId)->count();
            $content['class_count'] = ClassApp::where('study_program_id', $studyProgramId)->count();
            // Untuk ClassTopic, karena tidak memiliki study_program_id langsung, 
            // kita perlu memfilter melalui relasi "class"
            $content['meeting_count'] = ClassTopic::whereHas('class', function ($query) use ($studyProgramId) {
                $query->where('study_program_id', $studyProgramId);
            })->count();
            // Ambil list data dengan format yang diminta
            $content['students'] = Student::where('study_program_id', $studyProgramId)
                ->select('first_name', 'last_name')
                ->get()
                ->map(function ($student) {
                    return [
                        'label' => $student->first_name . ' ' . $student->last_name,
                    ];
                });

            $content['lecturers'] = Lecture::where('study_program_id', $studyProgramId)
                ->select('first_name', 'last_name')
                ->get()
                ->map(function ($lecture) {
                    return [
                        'label' => $lecture->first_name . ' ' . $lecture->last_name,
                    ];
                });

            $content['classes'] = ClassApp::where('study_program_id', $studyProgramId)
                ->select('class_name_long')
                ->get()
                ->map(function ($class) {
                    return [
                        'label' => $class->class_name_long,
                    ];
                });

            $content['meetings'] = ClassTopic::whereHas('class', function ($query) use ($studyProgramId) {
                $query->where('study_program_id', $studyProgramId);
            })->select('title')
                ->get()
                ->map(function ($topic) {
                    return [
                        'label' => $topic->title,
                    ];
                });
        } 

        return response()->json($content);
    }

    public function student(Request $request){
        $user = Auth::user();
        
        $student = Student::where('user_id', $user->id)->first();
        $studentId = $student->id;
        
        // Cek apakah user adalah mahasiswa
        if (empty($student)) {
            return response()->json([
                'message' => 'Unauthorized: User is not a student'
            ], 403);
        }

        $classes = ClassApp::whereHas('students', function($query) use ($studentId) {
                $query->where('id', $studentId);
            })
            ->with(['studyProgram', 'period', 'lecturer'])
            ->paginate(10);

        return response()->json([
            'data' => $classes->items(),
            'pagination' => [
                'total' => $classes->total(),
                'per_page' => $classes->perPage(),
                'current_page' => $classes->currentPage(),
                'last_page' => $classes->lastPage()
            ]
        ]);
    }
}
