<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpsCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ops_cash_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->enum('type', ['in', 'out']);
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->foreign('academic_year_id')->references('id')->on('acad_academic_years')->onDelete('CASCADE');
            $table->foreign('class_id')->references('id')->on('acad_classes')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('sys_users')->onDelete('CASCADE');
            $table->index(['academic_year_id']);
            $table->index(['user_id']);
            $table->index(['class_id', 'academic_year_id', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ops_cash_flows');
    }
}