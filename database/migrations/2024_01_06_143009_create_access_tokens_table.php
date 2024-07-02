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
        Schema::create('ghl_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->string('token_type');
            $table->integer('expires_in');
            $table->text('refresh_token');
            $table->text('scope');
            $table->string('userType');
            $table->string('companyId');
            $table->string('userId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ghl_access_tokens');
    }
};
