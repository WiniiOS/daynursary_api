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
          
        Schema::create('job_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('pronoun')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('post_code')->nullable();
            $table->text('work_eligibility')->nullable();
            $table->text('languages')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->boolean('open_to_opportunities')->default(false);
            $table->boolean('actively_looking')->default(false);

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profiles');
    }
};
