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
        Schema::table('centers', function (Blueprint $table) {
            $table->unsignedBigInteger('center_admin_id')->nullable();
            $table->foreign('center_admin_id')->references('id')->on('center_admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('centers', function (Blueprint $table) {
            $table->dropForeign(['center_admin_id']);
            $table->dropColumn('center_admin_id');
        });

    }
};
