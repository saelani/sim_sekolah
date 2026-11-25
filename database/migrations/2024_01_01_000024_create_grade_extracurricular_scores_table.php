<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeExtracurricularScoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_extracurricular_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('extracurricular_id');
            $table->enum('grade', ['A', 'B', 'C', 'D'])->default('B');
            $table->text('description');
            $table->foreign('enrollment_id')->references('id')->on('acad_enrollments')->onDelete('CASCADE');
            $table->foreign('extracurricular_id')->references('id')->on('grade_extracurriculars')->onDelete('CASCADE');
            $table->index(['enrollment_id']);
            $table->index(['extracurricular_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_extracurricular_scores');
    }
}