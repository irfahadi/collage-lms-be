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
        Schema::create('study_program', function (Blueprint $table) {
            $table->id(); // id BIGINT AUTO_INCREMENT PRIMARY KEY
            $table->string('name', 100);
            $table->string('code', 10);
            $table->text('description')->nullable();
            $table->string('head_of_program', 100)->nullable();
            $table->integer('established_year')->nullable();
            $table->string('contact_email', 254)->nullable();
            $table->string('contact_phone', 15)->nullable();

            // Foreign key ke faculties
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_program');
    }
};
