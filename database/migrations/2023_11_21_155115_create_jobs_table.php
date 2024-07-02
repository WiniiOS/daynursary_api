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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('job_type');
            $table->text('job_info');
            $table->string('service_to_render');
            $table->date('start_date');
            $table->decimal('min_pay', 10, 2)->nullable();
            $table->decimal('max_pay', 10, 2);
            $table->string('pay_type');
            $table->text('about_applicant');
            $table->string('language');
            $table->text('eligibility');
            $table->unsignedBigInteger('center_id');
            $table->timestamps();

            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
