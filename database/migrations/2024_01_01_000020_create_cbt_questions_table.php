<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCbtQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cbt_exam_id');
            $table->enum('type', ['single_choice', 'multiple_choice', 'short_answer', 'essay'])->default('single_choice');
            $table->longText('question_text');
            $table->string('media_path', 255)->nullable();
            $table->tinyInteger('score_weight')->default('1');
            $table->foreign('cbt_exam_id')->references('id')->on('cbt_exams')->onDelete('CASCADE');
            $table->index(['cbt_exam_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_questions');
    }
}