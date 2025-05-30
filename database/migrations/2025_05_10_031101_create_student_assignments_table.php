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
        Schema::create('student_assignment', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_file');
            $table->string('lecture_feedback')->nullable();
            $table->date('revision_date')->nullable();

            $table->foreignId('topic_assignment_id')->constrained('topic_assignment')->onDelete('cascade');
            $table->foreignId('class_topic_id')->constrained('class_topic')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('student')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assignment');
    }
};
