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
        Schema::create('parent_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_profile_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->date('dob');
            $table->string('gender');
            $table->string('centrelink');
            $table->text('child_allergies')->nullable();
            $table->text('special_needs')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('parent_profile_id')->references('id')->on('parent_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_children');
    }
};
