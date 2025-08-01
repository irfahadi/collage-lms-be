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
        Schema::table('lecture', function (Blueprint $table) {
            if (Schema::hasColumn('lecture', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecture', function (Blueprint $table) {
            $table->string('profile_picture')->nullable(false)->change();
        });
    }
};
