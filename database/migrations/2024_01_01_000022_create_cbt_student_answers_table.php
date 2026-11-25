<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCbtStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cbt_student_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('cbt_exam_session_id')->nullable();
            $table->unsignedBigInteger('cbt_question_id');
            $table->unsignedBigInteger('cbt_option_id')->nullable();
            $table->text('answer_text');
            $table->text('essay_answer');
            $table->boolean('is_correct')->nullable();
            $table->decimal('score_given', 5, 2)->nullable();
            $table->foreign('cbt_exam_session_id')->references('id')->on('cbt_exam_sessions')->onDelete('CASCADE');
            $table->foreign('cbt_option_id')->references('id')->on('cbt_options')->onDelete('SET NULL');
            $table->foreign('cbt_question_id')->references('id')->on('cbt_questions')->onDelete('CASCADE');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->unique(['cbt_exam_session_id', 'cbt_question_id']);
            $table->index(['cbt_question_id']);
            $table->index(['cbt_option_id']);
            $table->index(['student_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_student_answers');
    }
}