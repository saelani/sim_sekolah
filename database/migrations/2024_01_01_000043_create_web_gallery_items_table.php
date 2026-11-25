<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebGalleryItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gallery_id');
            $table->enum('type', ['image', 'video_url'])->default('image');
            $table->string('file_or_url', 255);
            $table->string('caption', 255)->nullable();
            $table->foreign('gallery_id')->references('id')->on('web_galleries')->onDelete('CASCADE');
            $table->index(['gallery_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_gallery_items');
    }
}