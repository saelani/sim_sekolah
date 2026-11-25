<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeFormatifsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_formatifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('learning_objective_id');
            $table->string('type', 255)->nullable();
            $table->tinyInteger('score')->nullable();
            $table->boolean('is_achieved')->default('1');
            $table->foreign('enrollment_id')->references('id')->on('acad_enrollments')->onDelete('CASCADE');
            $table->foreign('learning_objective_id')->references('id')->on('grade_learning_objectives')->onDelete('CASCADE');
            $table->unique(['enrollment_id', 'learning_objective_id']);
            $table->index(['learning_objective_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_formatifs');
    }
}