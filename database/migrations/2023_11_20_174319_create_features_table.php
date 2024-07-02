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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['parent', 'child'])->default('parent');
            $table->string('image')->nullable();
            $table->string('for');
            $table->unsignedBigInteger('parent_feature_id')->nullable();
           
            $table->text('description')->nullable();
            // Foreign key constraint for parent feature
            $table->foreign('parent_feature_id')->references('id')->on('features')->onDelete('cascade');
       
            $table->timestamps();
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
