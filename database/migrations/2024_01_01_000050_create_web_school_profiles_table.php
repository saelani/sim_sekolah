<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSchoolProfilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 10);
            $table->string('school_name', 255);
            $table->string('headmaster_name', 255)->nullable();
            $table->char('accreditation', 1)->default('A');
            $table->text('address');
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->text('vision');
            $table->text('mission');
            $table->string('logo_path', 255)->nullable();
            $table->text('maps_embed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_school_profiles');
    }
}