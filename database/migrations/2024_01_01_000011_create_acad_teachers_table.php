<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcadTeachersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acad_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nip', 18)->nullable();
            $table->string('nuptk', 16)->nullable();
            $table->string('name', 255);
            $table->string('front_title', 50)->nullable();
            $table->string('back_title', 50)->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('photo_path', 255)->nullable();
            $table->enum('role_type', ['headmaster', 'class_teacher', 'subject_teacher'])->default('class_teacher');
            $table->boolean('is_active')->default('1');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('SET NULL');
            $table->unique(['nip']);
            $table->unique(['nuptk']);
            $table->index(['user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_teachers');
    }
}