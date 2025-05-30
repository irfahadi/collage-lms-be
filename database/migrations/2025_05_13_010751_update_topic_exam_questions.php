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
        Schema::table('topic_exam_questions', function (Blueprint $table) {
            $table->foreignId('score_type_id')->constrained('score_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topic_exam_questions', function (Blueprint $table) {
            $table->dropColumn('score_type_id');
        });
    }
};
