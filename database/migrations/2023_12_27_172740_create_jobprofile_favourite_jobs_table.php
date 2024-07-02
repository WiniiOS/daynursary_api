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
        Schema::create('job_profile_favourite_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jobprofile_id');
            $table->unsignedBigInteger('job_id');
            $table->timestamps();

            $table->foreign('jobprofile_id')->references('id')->on('job_profiles')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profile_favourite_jobs');
    }
};
