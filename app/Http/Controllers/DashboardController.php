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
        // dd($user);
        // $studyProgramId = $user->study_program_id;

        $content = [];
        // $content = ['message' => 'Hello, World!'];

        if ($user->role_id === 2) {
            // $content['message'] = 'Hello, Manager!';
            $content['student_count'] = Student::count();
            $content['lecturer_count'] = Lecture::count();
            $content['class_count'] = ClassApp::count();
            $content['meeting_count'] = ClassTopic::count();
        } 
        return response()->json($content);
    }

    public function student(Request $request){
        $user = Auth::user();
        
        // Cek apakah user adalah mahasiswa
        if (!$user->student) {
            return response()->json([
                'message' => 'Unauthorized: User is not a student'
            ], 403);
        }

        $studentId = $user->student->id;

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
