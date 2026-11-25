<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebHeroSlidersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_hero_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('subtitle');
            $table->string('image_path', 255);
            $table->string('button_text', 50)->nullable();
            $table->string('button_url', 255)->nullable();
            $table->tinyInteger('order_number')->default('0');
            $table->boolean('is_active')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_hero_sliders');
    }
}