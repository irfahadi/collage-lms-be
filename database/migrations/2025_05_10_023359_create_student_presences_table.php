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
        Schema::create('student_presence', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_topic_id')->constrained('class_topic')->onDelete('cascade');
            $table->foreignId('presence_type_id')->constrained('presence_types')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('student')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_presence');
    }
};
