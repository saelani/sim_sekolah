<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('class_level', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->longText('content');
            $table->string('file_path', 255)->nullable();
            $table->string('external_url', 255)->nullable();
            $table->boolean('is_active')->default('1');
            $table->foreign('subject_id')->references('id')->on('acad_subjects')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('SET NULL');
            $table->unique(['slug']);
            $table->index(['subject_id']);
            $table->index(['user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
}