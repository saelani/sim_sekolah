<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeLearningObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_learning_objectives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('sumative_scope_id')->nullable();
            $table->enum('phase', ['A', 'B', 'C']);
            $table->tinyInteger('level');
            $table->enum('semester', ['1', '2']);
            $table->string('code', 20);
            $table->text('description');
            $table->foreign('subject_id')->references('id')->on('acad_subjects')->onDelete('CASCADE');
            $table->foreign('sumative_scope_id')->references('id')->on('grade_sumative_scopes')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('SET NULL');
            $table->index(['subject_id', 'phase', 'level', 'semester']);
            $table->index(['sumative_scope_id']);
            $table->index(['teacher_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_learning_objectives');
    }
}