<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCbtExamsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cbt_exams', function (Blueprint $table) {
            $table->id();
            $table->string('assessment_type', 255)->default('formatif');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('learning_objective_id')->nullable();
            $table->unsignedBigInteger('sumative_scope_id')->nullable();
            $table->unsignedBigInteger('class_room_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->string('title', 255);
            $table->unsignedSmallInteger('duration_minutes');
            $table->string('token', 10)->nullable();
            $table->boolean('randomize_questions')->default('1');
            $table->boolean('randomize_options')->default('1');
            $table->boolean('show_result')->default('0');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('is_active')->default('0');
            $table->foreign('class_room_id')->references('id')->on('acad_classes')->onDelete('SET NULL');
            $table->foreign('subject_id')->references('id')->on('acad_subjects')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('CASCADE');
            $table->index(['subject_id']);
            $table->index(['teacher_id']);
            $table->index(['class_room_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_exams');
    }
}