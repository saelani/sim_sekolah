<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeFinalScoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_final_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('subject_id');
            $table->decimal('formative_avg', 5, 2)->default('0.00');
            $table->decimal('sumative_slm_avg', 5, 2)->default('0.00');
            $table->decimal('sumative_sas', 5, 2)->default('0.00');
            $table->decimal('final_score', 5, 2)->default('0.00');
            $table->text('highest_achieved_description');
            $table->text('lowest_achieved_description');
            $table->foreign('enrollment_id')->references('id')->on('acad_enrollments')->onDelete('CASCADE');
            $table->foreign('subject_id')->references('id')->on('acad_subjects')->onDelete('CASCADE');
            $table->unique(['enrollment_id', 'subject_id']);
            $table->index(['subject_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_final_scores');
    }
}