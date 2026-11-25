<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeSumatifsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_sumatifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('sumative_scope_id')->nullable();
            $table->enum('type', ['SLM', 'SAS']);
            $table->tinyInteger('score');
            $table->foreign('enrollment_id')->references('id')->on('acad_enrollments')->onDelete('CASCADE');
            $table->foreign('subject_id')->references('id')->on('acad_subjects')->onDelete('CASCADE');
            $table->foreign('sumative_scope_id')->references('id')->on('grade_sumative_scopes')->onDelete('CASCADE');
            $table->index(['subject_id']);
            $table->index(['sumative_scope_id']);
            $table->index(['enrollment_id', 'subject_id', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_sumatifs');
    }
}