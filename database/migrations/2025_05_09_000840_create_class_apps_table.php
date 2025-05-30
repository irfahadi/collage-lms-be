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
        Schema::create('class', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY

            $table->string('class_code', 15);
            $table->string('class_name_long', 100);
            $table->string('class_name_short', 10);
            $table->integer('class_availability');
            $table->integer('visibility');
            $table->text('description');
            $table->text('class_thumbnail')->nullable();
            $table->string('tag', 50);

            // Foreign keys
            $table->foreignId('responsible_lecturer_id')->constrained('lecture')->onDelete('cascade');
            $table->foreignId('study_program_id')->constrained('study_program')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_apps');
    }
};
