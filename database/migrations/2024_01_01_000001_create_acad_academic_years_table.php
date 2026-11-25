<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcadAcademicYearsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acad_academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('year', 9);
            $table->enum('semester', ['1', '2']);
            $table->boolean('is_active')->default('0');
            $table->index(['is_active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_academic_years');
    }
}