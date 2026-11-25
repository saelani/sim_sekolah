<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeP5ScoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_p5_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('p5_subelement_id');
            $table->enum('score', ['BB', 'MB', 'BSH', 'SB']);
            $table->foreign('enrollment_id')->references('id')->on('acad_enrollments')->onDelete('CASCADE');
            $table->foreign('p5_subelement_id')->references('id')->on('grade_p5_subelements')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('SET NULL');
            $table->unique(['enrollment_id', 'p5_subelement_id']);
            $table->index(['p5_subelement_id']);
            $table->index(['teacher_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_p5_scores');
    }
}