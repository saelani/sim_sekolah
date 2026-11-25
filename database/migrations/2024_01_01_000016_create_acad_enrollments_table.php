<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcadEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acad_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('academic_year_id')->references('id')->on('acad_academic_years')->onDelete('CASCADE');
            $table->foreign('class_id')->references('id')->on('acad_classes')->onDelete('CASCADE');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->unique(['academic_year_id', 'student_id']);
            $table->index(['class_id']);
            $table->index(['student_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_enrollments');
    }
}