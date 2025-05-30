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
        Schema::create('score_setting', function (Blueprint $table) {
            $table->id();
            $table->decimal('percent_value', 5, 2); // Misal skor maksimal 999.99
            
            $table->foreignId('class_id')->constrained('class')->onDelete('cascade');
            $table->foreignId('score_type_id')->constrained('score_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_setting');
    }
};
