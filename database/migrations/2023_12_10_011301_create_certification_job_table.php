<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certification_job', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certification_id');
            $table->unsignedBigInteger('job_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('certification_id')->references('id')->on('certifications')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_job');
    }
};
