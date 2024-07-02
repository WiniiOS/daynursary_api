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

        Schema::create('profile_educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_profile_id');
            $table->string('qualification');
            $table->string('description')->nullable();
            $table->string('field_of_study');
            $table->string('school');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('currently_studying')->default(false);
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
        Schema::dropIfExists('profile_educations');
    }
};
