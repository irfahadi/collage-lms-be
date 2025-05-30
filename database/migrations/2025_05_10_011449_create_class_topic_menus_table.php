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
        Schema::create('class_topic_menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu');
            $table->boolean('is_modul');
            $table->boolean('is_exam');

            $table->foreignId('class_topic_id')->constrained('class_topic')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_topic_menus');
    }
};
