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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique(); // Format: ID00001/2026
            $table->string('title');
            $table->text('description');

            $table->string('category', 20)->nullable();
            $table->foreign('category')->references('code')->on('codes')->onDelete('set null');

            $table->string('urgency', 20)->nullable();
            $table->foreign('urgency')->references('code')->on('codes')->onDelete('set null');

            $table->string('status', 20)->default('NEW'); // New, Pending, Closed, Done, Reopen
            $table->foreign('status')->references('code')->on('codes')->onDelete('set null');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('assigned_to')->nullable();

            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
