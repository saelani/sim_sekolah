<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCbtExamSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cbt_exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cbt_exam_id');
            $table->unsignedBigInteger('enrollment_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->dateTime('start_time');
            $table->string('max_end_time')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->string('status', 20)->default('ongoing');
            $table->decimal('score', 5, 2)->default('0.00');
            $table->decimal('total_score', 5, 2)->nullable();
            $table->foreign('cbt_exam_id')->references('id')->on('cbt_exams')->onDelete('CASCADE');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->unique(['cbt_exam_id', 'student_id']);
            $table->index(['student_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_exam_sessions');
    }
}