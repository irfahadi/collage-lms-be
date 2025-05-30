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
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();

            $table->decimal('score', 5, 2); // Misal skor maksimal 999.99

            // Foreign key references
            $table->foreignId('student_id')->constrained('student')->onDelete('cascade');
            $table->bigInteger('score_setting_id');
            $table->bigInteger('class_topic_id');
            // $table->foreignId('score_setting_id')->constrained('score_setting')->onDelete('cascade');
            // $table->foreignId('class_topic_id')->constrained('class_topic')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};
