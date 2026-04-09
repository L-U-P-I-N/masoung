<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using a more compatible way for SQLite and avoiding Doctrine DBAL requirement
        if (DB::getDriverName() === 'sqlite') {
            // SQLite is flexible with types, but one way to ensure 'TEXT' is to recreate if needed.
            // However, simply adding a new column and swapping is often safer.
            Schema::table('members', function (Blueprint $table) {
                $table->text('phone_new')->nullable();
            });
            
            DB::statement('UPDATE members SET phone_new = phone');
            
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
            
            Schema::table('members', function (Blueprint $table) {
                $table->renameColumn('phone_new', 'phone');
            });
        } else {
            Schema::table('members', function (Blueprint $table) {
                $table->text('phone')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('phone', 50)->nullable()->change();
        });
    }
};
