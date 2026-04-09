<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite لا يدعم تعديل الأعمدة مباشرةً — نستخدم raw SQL
        DB::statement('CREATE TABLE activities_new AS SELECT * FROM activities');
        DB::statement('DROP TABLE activities');
        DB::statement('
            CREATE TABLE activities (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT NOT NULL,
                content TEXT,
                image TEXT,
                activity_date DATE NOT NULL,
                location TEXT,
                is_published INTEGER NOT NULL DEFAULT 1,
                created_at DATETIME,
                updated_at DATETIME
            )
        ');
        DB::statement('INSERT INTO activities SELECT * FROM activities_new');
        DB::statement('DROP TABLE activities_new');

        DB::statement('CREATE TABLE news_new AS SELECT * FROM news');
        DB::statement('DROP TABLE news');
        DB::statement('
            CREATE TABLE news (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                excerpt TEXT NOT NULL,
                content TEXT NOT NULL,
                image TEXT,
                is_published INTEGER NOT NULL DEFAULT 1,
                published_at DATETIME,
                created_at DATETIME,
                updated_at DATETIME
            )
        ');
        DB::statement('INSERT INTO news SELECT * FROM news_new');
        DB::statement('DROP TABLE news_new');
    }

    public function down(): void
    {
        // إرجاع العمود إلى varchar — لن يضيع البيانات لأن SQLite يتسامح مع الأطوال
        Schema::table('activities', function (Blueprint $table) {
            // لا حاجة لتغيير الإرجاع في SQLite
        });
        Schema::table('news', function (Blueprint $table) {
            // لا حاجة لتغيير الإرجاع في SQLite
        });
    }
};
