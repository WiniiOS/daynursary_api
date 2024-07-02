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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // Add sender_type column
            $table->unsignedBigInteger('recipient_id');
            $table->string('recipient_type'); // Add recipient_type column
           // $table->unsignedBigInteger('center_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->string('application_type')->nullable();
            $table->text('title');
            $table->string('type')->nullable();
            $table->timestamps();


            // Foreign key constraints
            // $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
            // $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
