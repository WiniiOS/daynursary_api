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
        Schema::create('application_center_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_care_application_id');
            $table->unsignedBigInteger('center_service_id');
            // Add any other columns you need for the pivot table

            $table->foreign('child_care_application_id')->references('id')->on('child_care_applications')->onDelete('cascade');
            $table->foreign('center_service_id')->references('id')->on('center_services')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_center_service');
    }
};
