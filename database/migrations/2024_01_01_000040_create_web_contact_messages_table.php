<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name', 100);
            $table->string('email_or_phone', 100);
            $table->string('subject', 200);
            $table->text('message');
            $table->boolean('is_read')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_contact_messages');
    }
}