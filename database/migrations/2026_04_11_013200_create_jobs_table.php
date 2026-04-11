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
        Schema::create('jobs', function (Blueprint $blueprint) {
            $blueprint->bigIncrements('id');
            $blueprint->string('queue')->index();
            $blueprint->longText('payload');
            $blueprint->tinyInteger('attempts')->unsigned();
            $blueprint->integer('reserved_at')->unsigned()->nullable();
            $blueprint->integer('available_at')->unsigned();
            $blueprint->integer('created_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
