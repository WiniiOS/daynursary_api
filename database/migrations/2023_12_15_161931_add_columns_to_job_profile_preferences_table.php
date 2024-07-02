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
        Schema::table('job_profile_preferences', function (Blueprint $table) {
            $table->dropColumn('distance_range_min');
            $table->dropColumn('distance_range_max');
            $table->dropColumn('availability');
            $table->dropColumn('specific_date');
            $table->dropColumn('days_available'); 
            $table->dropColumn('work_type');
            $table->dropColumn('salary_range_min');
            $table->dropColumn('salary_range_max');
            //add columns
            $table->json('jobs_interested')->nullable();
            $table->json('companies_selection')->nullable();
            $table->json('salary')->nullable();
            $table->integer('distance_covered')->default(0);
            $table->string('start_type')->nullable();
            $table->date('start_date')->nullable();
            $table->json('days')->nullable();
            $table->json('jobs')->nullable();
        });
    }
 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_profile_preferences', function (Blueprint $table) {
            //drop the columns added
            $table->dropColumn('jobs_interested');
            $table->dropColumn('companies_selection');
            $table->dropColumn('salary');
            $table->dropColumn('distance_covered');
            $table->dropColumn('start_type');
            $table->dropColumn('start_date');
            $table->dropColumn('days');
            $table->dropColumn('jobs');
            //recreate the deleted columns
            $table->integer('distance_range_min')->nullable();
            $table->integer('distance_range_max')->nullable();
            $table->enum('availability', ['now', 'specific_date'])->default('now');
            $table->date('specific_date')->nullable();
            $table->json('days_available')->nullable();
            $table->string('work_type')->nullable();
            $table->integer('salary_range_min')->nullable();
            $table->integer('salary_range_max')->nullable();

        });
    }
};
