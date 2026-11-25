<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpsStudentAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ops_student_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('date');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa'])->default('Hadir');
            $table->string('notes', 255)->nullable();
            $table->foreign('class_id')->references('id')->on('acad_classes')->onDelete('CASCADE');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('SET NULL');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('CASCADE');
            $table->unique(['student_id', 'date']);
            $table->index(['class_id', 'date', 'status']);
            $table->index(['teacher_id']);
            $table->index(['user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ops_student_attendances');
    }
}