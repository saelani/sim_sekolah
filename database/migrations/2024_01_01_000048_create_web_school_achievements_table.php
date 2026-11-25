<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSchoolAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_school_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->enum('category', ['Akademik', 'Non-Akademik']);
            $table->enum('level', ['Kecamatan', 'Kota/Kab', 'Provinsi', 'Nasional', 'Internasional']);
            $table->string('winner_name', 255);
            $table->date('achievement_date');
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_school_achievements');
    }
}