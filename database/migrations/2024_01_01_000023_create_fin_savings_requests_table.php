<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinSavingsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fin_savings_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('date')->nullable();
            $table->enum('type', ['deposit', 'withdrawal'])->default('deposit');
            $table->decimal('amount', 12, 2);
            $table->string('notes', 255)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('approved_at')->nullable();
            $table->foreign('approved_by')->references('id')->on('sys_users')->onDelete('SET NULL');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->index(['approved_by']);
            $table->index(['student_id', 'status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_savings_requests');
    }
}