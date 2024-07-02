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
        Schema::create('child_care_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('center_id');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('additional_parent_id')->nullable();
            $table->unsignedBigInteger('child_id');
            $table->string('status');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('parent_profiles')->onDelete('cascade');
            $table->foreign('additional_parent_id')->references('id')->on('parent_profiles')->onDelete('set null');
            $table->foreign('child_id')->references('id')->on('parent_children')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_care_applications');
    }
};
