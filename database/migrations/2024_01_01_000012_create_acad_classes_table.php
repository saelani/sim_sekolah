<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcadClassesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acad_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year_id');
            $table->string('name', 50);
            $table->enum('phase', ['A', 'B', 'C']);
            $table->tinyInteger('level');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('acad_academic_years')->onDelete('CASCADE');
            $table->foreign('teacher_id')->references('id')->on('acad_teachers')->onDelete('SET NULL');
            $table->index(['teacher_id']);
            $table->index(['academic_year_id', 'level', 'phase']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_classes');
    }
}