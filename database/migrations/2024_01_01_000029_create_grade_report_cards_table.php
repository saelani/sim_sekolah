<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeReportCardsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_report_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->tinyInteger('sick')->default('0');
            $table->tinyInteger('permission')->default('0');
            $table->tinyInteger('unexcused')->default('0');
            $table->text('extracurricular_notes');
            $table->text('teacher_notes');
            $table->string('height_weight', 100)->nullable();
            $table->text('health_notes');
            $table->boolean('is_promoted')->nullable();
            $table->boolean('is_locked')->default('0');
            $table->string('place_date_printed', 150)->nullable();
            $table->string('printed_at')->nullable();
            $table->foreign('enrollment_id')->references('id')->on('acad_enrollments')->onDelete('CASCADE');
            $table->unique(['enrollment_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_report_cards');
    }
}