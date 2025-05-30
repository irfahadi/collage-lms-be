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
        Schema::create('topic_modules', function (Blueprint $table) {
            $table->id();
            $table->string('modul');
            $table->string('content');
            $table->text('description')->nullable();

            $table->foreignId('modul_type_id')->constrained('modul_types')->onDelete('cascade');
            $table->foreignId('class_topic_id')->constrained('class_topic')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_modules');
    }
};
