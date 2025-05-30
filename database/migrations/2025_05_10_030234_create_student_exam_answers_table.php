<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_exam_answer', function (Blueprint $table) {
            $table->id();
            $table->string('student_answer');

            $table->foreignId('topic_exam_question_id')->constrained('topic_exam_questions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('student')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exam_answer');
    }
};
