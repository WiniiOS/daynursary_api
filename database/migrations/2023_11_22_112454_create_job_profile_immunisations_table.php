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
        Schema::create('job_profile_immunisations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_profile_id');
            $table->unsignedBigInteger('immunisation_id');
            $table->date('vaccination_date')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('job_profile_id')->references('id')->on('job_profiles')->onDelete('cascade');
            $table->foreign('immunisation_id')->references('id')->on('immunisations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profile_immunisations');
    }
};
