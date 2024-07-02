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
        //
        Schema::table('profile_certifications', function (Blueprint $table) {
                $table->date('issue_date')->nullable()->change();
                $table->boolean('certificate_does_not_expire')->nullable()->default(false)->change();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('profile_certifications', function (Blueprint $table) {
            $table->date('issue_date')->change();
            $table->boolean('certificate_does_not_expire')->default(false)->change();
        });
    }
};
