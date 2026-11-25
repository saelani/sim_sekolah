<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeP5SubelementsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_p5_subelements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('p5_project_id');
            $table->string('dimension', 255);
            $table->string('element', 255);
            $table->text('subelement_name');
            $table->text('target_narrative');
            $table->foreign('p5_project_id')->references('id')->on('grade_p5_projects')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('SET NULL');
            $table->index(['p5_project_id']);
            $table->index(['teacher_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_p5_subelements');
    }
}