<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCbtOptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cbt_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cbt_question_id');
            $table->text('option_text');
            $table->string('media_path', 255)->nullable();
            $table->boolean('is_correct')->default('0');
            $table->foreign('cbt_question_id')->references('id')->on('cbt_questions')->onDelete('CASCADE');
            $table->index(['cbt_question_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_options');
    }
}