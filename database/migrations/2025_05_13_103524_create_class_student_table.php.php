<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassStudentTable extends Migration
{
    public function up()
    {
        Schema::create('class_student', function (Blueprint $table) {
            // Jika ingin composite PK tanpa id:
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('student_id');

            $table->primary(['class_id', 'student_id']);

            $table->foreign('class_id')
                  ->references('id')->on('class')
                  ->onDelete('cascade');

            $table->foreign('student_id')
                  ->references('id')->on('student')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_student');
    }
}