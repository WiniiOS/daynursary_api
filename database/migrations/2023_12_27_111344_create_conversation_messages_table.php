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
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('user_type');
            $table->unsignedBigInteger('user_id');
            $table->text('message')->nullable();
            $table->string('status')->nullable(); 
            $table->timestamps(); 
        
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
           
        });     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
    }
};
