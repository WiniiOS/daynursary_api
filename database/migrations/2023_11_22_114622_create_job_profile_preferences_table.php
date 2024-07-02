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
        Schema::create('job_profile_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_profile_id');
            $table->integer('distance_range_min')->nullable();
            $table->integer('distance_range_max')->nullable();
            $table->enum('availability', ['now', 'specific_date'])->default('now');
            $table->date('specific_date')->nullable();
            $table->json('days_available')->nullable();
            $table->string('work_type')->nullable();
            $table->integer('salary_range_min')->nullable();
            $table->integer('salary_range_max')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('job_profile_id')->references('id')->on('job_profiles')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profile_preferences');
    }
};
