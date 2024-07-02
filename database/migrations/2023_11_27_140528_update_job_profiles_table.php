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
        Schema::table('job_profiles', function (Blueprint $table) {
             $table->dropColumn(['country', 'state', 'city']);
              $table->foreignId('country_id')->nullable()->constrained('countries');
              $table->foreignId('state_id')->nullable()->constrained('states');
              $table->foreignId('city_id')->nullable()->constrained('cities');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_profiles', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['country_id', 'state_id', 'city_id']);

            // Add back the nullable columns (if necessary)
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
        });
    }
};
