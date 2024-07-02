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
        Schema::table('job_profile_skills', function (Blueprint $table) {
            
             
                $table->string('skill_level')->nullable()->change();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_profile_skills', function (Blueprint $table) {
            $table->integer('skill_level')->nullable()->change();
        });
    }
};
