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
        Schema::table('tribe_settings', function (Blueprint $table) {
            $table->dateTime('auto_approve_start')->nullable();
            $table->dateTime('auto_approve_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tribe_settings', function (Blueprint $table) {
            $table->dropColumn(['auto_approve_start', 'auto_approve_end']);
        });
    }
};
