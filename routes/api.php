<?php

use App\Http\Middleware\VerifyJwtToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicAssignmentController;
use App\Http\Controllers\ClassStudentController;
use App\Http\Controllers\ScoreSettingController;
use App\Http\Controllers\StudentScoreController;
use App\Http\Controllers\StudentExamAnswerController;
use App\Http\Controllers\StudentPresenceScoreController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\ClassTopicMenuController;

Route::post('auth/register', \App\Http\Controllers\Api\Auth\RegisterController::class);
//Route::post('auth/login', \App\Http\Controllers\Api\Auth\LoginController::class);
Route::post('/password/reset', [\App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'reset']); 
Route::middleware([VerifyJwtToken::Class])->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']); 
Route::middleware([VerifyJwtToken::Class])->get('/dashboard/student', [\App\Http\Controllers\DashboardController::class, 'student']); 

Route::middleware([VerifyJwtToken::Class])->apiResource('/user', App\Http\Controllers\Api\UserController::class);
Route::middleware([VerifyJwtToken::Class])->apiResource('/role', App\Http\Controllers\RoleController::class);
Route::middleware([VerifyJwtToken::Class])->apiResource('/faculty', App\Http\Controllers\FacultyController::class);
Route::middleware([VerifyJwtToken::Class])->apiResource('/study_program', App\Http\Controllers\StudyProgramController::class);
Route::middleware([VerifyJwtToken::Class])->apiResource('/period', App\Http\Controllers\PeriodController::class);
Route::middleware([VerifyJwtToken::Class])->apiResource('/class', App\Http\Controllers\ClassAppController::class);
Route::middleware([VerifyJwtToken::Class])->apiResource('/topic', App\Http\Controllers\ClassTopicController::class);
Route::middleware([VerifyJwtToken::Class])->post('modules/update', [App\Http\Controllers\TopicModulController::class, 'bulkSyncModules']);
Route::middleware([VerifyJwtToken::Class])->get('modules', [App\Http\Controllers\TopicModulController::class, 'getModules']);
Route::middleware([VerifyJwtToken::Class])->post('questions/update', [App\Http\Controllers\TopicExamQuestionController::class, 'bulkSync']);
Route::middleware([VerifyJwtToken::Class])->get('questions', [App\Http\Controllers\TopicExamQuestionController::class, 'index']);
Route::middleware([VerifyJwtToken::Class])->get('lecturer', [App\Http\Controllers\ClassAppController::class, 'lecturers']);
Route::middleware([VerifyJwtToken::Class])->get('student', [ClassStudentController::class, 'students']);
Route::middleware([VerifyJwtToken::Class])->get('student/report', [StudentScoreController::class, 'report']);


Route::middleware([VerifyJwtToken::Class])->prefix('assignment')->group(function () {
    Route::get('/', [TopicAssignmentController::class, 'index']);
    Route::post('/', [TopicAssignmentController::class, 'store']);
    Route::put('/', [TopicAssignmentController::class, 'update']);
    Route::delete('/', [TopicAssignmentController::class, 'destroy']);
});

Route::middleware([VerifyJwtToken::Class])->prefix('class/{class}')->group(function () {
    Route::get('students',  [ClassStudentController::class, 'report']);
    Route::post('students', [ClassStudentController::class, 'store']);
    Route::delete('student/{student}', [ClassStudentController::class, 'destroy']);
    Route::get('score-setting', [ScoreSettingController::class, 'index']);
    Route::post('score-setting', [ScoreSettingController::class, 'store']);
    Route::post('student/presence', [StudentPresenceScoreController::class, 'store']);
    Route::get('student/presence', [StudentPresenceScoreController::class, 'getStudentPresence']);
    Route::get('student/assignment', [StudentAssignmentController::class, 'getStudentAssignments']);
    Route::get('student/assignment/{class_topic}', [StudentAssignmentController::class, 'getStudentAssignment']);
    Route::post('student/assignment', [StudentAssignmentController::class, 'upsertScore']);
    Route::get('topic/menu', [ClassTopicMenuController::class, 'index'])
        ->name('api.class.topics');
    // Route::get('student-scores/{class_topic_id}/{score_setting_id}', [StudentScoreController::class, 'getStudentScores']);
});

Route::middleware([VerifyJwtToken::Class])->prefix('topic/{class_topic}')->group(function () {
    Route::get('exam-question/score-type/{score_type}', [ClassTopicMenuController::class, 'getExamQuestions']);
    Route::post('exam-answer', [StudentExamAnswerController::class, 'submitAnswers']);
    Route::post('assignment', [StudentAssignmentController::class, 'submitAssignment']);
});





