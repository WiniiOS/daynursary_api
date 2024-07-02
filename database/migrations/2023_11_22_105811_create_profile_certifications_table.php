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
        Schema::create('profile_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_profile_id')->nullable();
            $table->unsignedBigInteger('certification_id');
            $table->string('issuing_organization');
            $table->date('issue_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('certificate_does_not_expire')->default(false);
            $table->unsignedBigInteger('issuer_id')->nullable();
            $table->string('issuer_url')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('job_profile_id')->references('id')->on('job_profiles')->onDelete('cascade');
            $table->foreign('certification_id')->references('id')->on('certifications')->onDelete('cascade');
            // You may add a foreign key constraint for issuer_id if you have an issuers table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_certifications');
    }
};
