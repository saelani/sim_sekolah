<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('date')->nullable();
            $table->enum('category', ['Sarana & Prasarana', 'Pembelajaran', 'Pelayanan', 'Lainnya'])->default('Lainnya');
            $table->string('subject', 255);
            $table->text('content');
            $table->boolean('is_anonymous')->default('0');
            $table->enum('status', ['pending', 'read', 'responded', 'resolved'])->default('pending');
            $table->text('response_notes');
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->string('responded_at')->nullable();
            $table->foreign('responded_by')->references('id')->on('sys_users')->onDelete('SET NULL');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->index(['responded_by']);
            $table->index(['student_id', 'status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_feedbacks');
    }
}