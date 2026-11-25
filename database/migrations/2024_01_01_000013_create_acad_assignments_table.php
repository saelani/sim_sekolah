<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcadAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acad_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->string('title', 255);
            $table->text('description');
            $table->dateTime('due_date');
            $table->foreign('class_id')->references('id')->on('acad_classes')->onDelete('CASCADE');
            $table->foreign('subject_id')->references('id')->on('acad_subjects')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('SET NULL');
            $table->index(['class_id']);
            $table->index(['subject_id']);
            $table->index(['teacher_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_assignments');
    }
}