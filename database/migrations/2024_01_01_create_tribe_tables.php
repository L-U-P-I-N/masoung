<?php
// database/migrations/2024_01_01_000001_create_tribe_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول الإعدادات العامة للقبيلة
        Schema::create('tribe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tribe_name')->default('قبيلة مسونق');
            $table->text('tribe_description')->nullable();
            $table->date('founded_date')->nullable();
            $table->string('location')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });

        // جدول الأعضاء
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable(); // المنصب / الدور
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // جدول الأنشطة
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->date('activity_date');
            $table->string('location')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // جدول الأخبار
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->text('content');
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // جدول الادمن
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('members');
        Schema::dropIfExists('tribe_settings');
        Schema::dropIfExists('admins');
    }
};
