<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpsStudentSavingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ops_student_savings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->enum('type', ['in', 'out']);
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->foreign('student_id')->references('id')->on('acad_students')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('CASCADE');
            $table->index(['user_id']);
            $table->index(['student_id', 'date', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ops_student_savings');
    }
}