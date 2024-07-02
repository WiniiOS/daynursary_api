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
        Schema::create('job_profile_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_profile_id');
            $table->string('type');
            $table->string('name');
            $table->string('link');
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
        Schema::dropIfExists('job_profile_documents');
    }
};
