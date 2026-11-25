<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('file_path', 255);
            $table->string('file_size', 20)->nullable();
            $table->unsignedInteger('download_count')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_downloads');
    }
}