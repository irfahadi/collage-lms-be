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
        Schema::create('class_topic_modul_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_topic_id')
                  ->constrained('class_topic')
                  ->onDelete('cascade');
            $table->foreignId('topic_modul_id')->nullable()
                  ->constrained('topic_modules')
                  ->onDelete('cascade');
            $table->foreignId('topic_exam_question_id')->nullable()
                  ->constrained('topic_exam_questions')
                  ->onDelete('cascade');
            $table->timestamps();

            // Pastikan kombinasi unik agar tidak duplikasi
            $table->unique(
                ['class_topic_id', 'topic_modul_id', 'topic_exam_question_id'],
                'ctmq_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_topic_modul_question');
    }
};
