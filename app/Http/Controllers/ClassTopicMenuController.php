<?php

namespace App\Http\Controllers;

use App\Models\ClassApp;
use App\Models\ClassTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassTopicMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClassApp $class)
    {
        $user = Auth::user();
        
        // Validasi akses mahasiswa
        if (!$user->student || !$class->students->contains($user->student)) {
            return response()->json([
                'message' => 'Unauthorized: Not enrolled in this class'
            ], 403);
        }

        // Ambil semua topik beserta menu-nya
        $topics = ClassTopic::with('classTopicMenu')
            ->where('class_id', $class->id)
            ->get();

        return response()->json([
            'class' => $class,
            'topics' => $topics->map(function($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'menus' => $topic->classTopicMenu->map(function($menu) {
                        return [
                            'id' => $menu->id,
                            'menu' => $menu->menu,
                            'is_modul' => $menu->is_modul,
                            'is_exam' => $menu->is_exam,
                            'created_at' => $menu->created_at->format('Y-m-d H:i:s')
                        ];
                    })
                ];
            })
        ]);
    }

    public function getModules(ClassTopic $classTopic)
    {
        $modules = $classTopic->modules()
            ->get();

        return response()->json([
            'data' => $modules
        ], 200);
    }

    public function getExamQuestions(ClassTopic $classTopic)
    {
        $examQuestions = $classTopic->examQuestions()
            ->get()
            ->map(function ($question) {
                // Format data pivot
                return $question;
            });

        return response()->json([
            'data' => $examQuestions
        ], 200);
    }
}
