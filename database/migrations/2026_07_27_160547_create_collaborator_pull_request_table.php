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
        Schema::create('collaborator_pull_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collaborator_id')->constrained()->onDelete('cascade');
            $table->foreignId('pull_request_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborator_pull_request');
    }
};
