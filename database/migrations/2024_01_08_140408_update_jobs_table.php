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
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['about_applicant','language','eligibility']);
            $table->json('work_eligibility')->nullable(false);
            $table->date('due_date')->nullable(); 
            $table->json('benefits')->nullable(false);  
        });
        //linked tables : languages, features,certifications,skills,immunisation
        // $table->json('language')->nullable(false)->change();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['due_date','benefits','work_eligibility']);
            $table->text('about_applicant');
            $table->string('language');
            $table->text('eligibility');     

        });
    }
};
