<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('centers', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['country', 'state', 'city']);

            // Add new foreign key columns
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();

            // Foreign key constraints
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('centers', function (Blueprint $table) {
            // Reverse the changes on rollback
            $table->string('country');
            $table->string('state');
            $table->string('city');

            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['city_id']);

            $table->dropColumn(['country_id', 'state_id', 'city_id']);
        });
    }
};
