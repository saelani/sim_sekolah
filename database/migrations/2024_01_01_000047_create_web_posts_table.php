<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebPostsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('excerpt');
            $table->longText('content');
            $table->string('thumbnail_path', 255)->nullable();
            $table->unsignedInteger('views_count')->default('0');
            $table->boolean('is_published')->default('1');
            $table->string('published_at')->nullable();
            $table->foreign('category_id')->references('id')->on('web_post_categories')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('CASCADE');
            $table->unique(['slug']);
            $table->index(['category_id']);
            $table->index(['user_id']);
            $table->index(['is_published', 'published_at', 'slug']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_posts');
    }
}