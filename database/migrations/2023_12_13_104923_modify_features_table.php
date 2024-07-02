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
        Schema::table('features', function (Blueprint $table) {
            $table->string('parent_feature_slug')->nullable()->after('id');
            $table->dropForeign(['parent_feature_id']);
            $table->dropColumn('parent_feature_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_feature_id')->nullable()->after('id');
            $table->foreign('parent_feature_id')->references('id')->on('features')->onDelete('cascade');
            $table->dropColumn('parent_feature_slug');
        }); 
    }
};
