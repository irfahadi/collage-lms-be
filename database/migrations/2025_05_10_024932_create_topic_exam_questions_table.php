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
        Schema::create('topic_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->boolean('is_essay');
            $table->boolean('is_multiple_choice');
            $table->string('multiple_choice_options')->nullable();
            $table->string('true_answer');

            $table->foreignId('class_topic_id')->constrained('class_topic')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_exam_questions');
    }
};
