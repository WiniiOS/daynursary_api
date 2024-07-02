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
        Schema::table('center_reviews', function (Blueprint $table) {
            // Make user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Add source and avatar columns
            $table->string('source')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {  

        Schema::table('center_reviews', function (Blueprint $table) {
            // Reverse changes if needed
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropColumn('source');
            $table->dropColumn('avatar');
        });
       
       
    }
};
