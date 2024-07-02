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
        Schema::create('book_a_tours', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('email');
            $table->integer('totalVisitors');
            $table->string('telephone');
            $table->string('child_first_name');
            $table->string('child_last_name');
            $table->json('childs')->nullable();
            $table->longtext('message');
            $table->string('choosed_time');
            $table->string('choosed_date');
            $table->boolean('done')->default(0);
            $table->timestamps();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_a_tours');
    }
};
