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
        Schema::table('student', function (Blueprint $table) {
            if (Schema::hasColumn('lecture', 'birthdate')) {
                $table->date('birthdate')->nullable()->change();
            }
            if (Schema::hasColumn('lecture', 'phone_number')) {
                $table->string('phone_number')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->date('birthdate')->nullable(false)->change();
            $table->string('phone_number')->nullable(false)->change();
        });
    }
};
