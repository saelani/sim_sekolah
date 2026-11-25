<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcadStudentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acad_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nis', 20);
            $table->string('nisn', 10);
            $table->string('name', 255);
            $table->enum('gender', ['L', 'P']);
            $table->enum('religion', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Budha'])->default('Islam');
            $table->string('pob', 100);
            $table->date('dob');
            $table->text('address');
            $table->string('parent_name', 255)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreign('class_id')->references('id')->on('acad_classes')->onDelete('SET NULL');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('SET NULL');
            $table->unique(['nis']);
            $table->unique(['nisn']);
            $table->index(['user_id']);
            $table->index(['religion', 'gender', 'name']);
            $table->index(['class_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_students');
    }
}