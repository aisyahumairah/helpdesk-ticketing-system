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
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('resolved_at')->nullable()->after('assigned_to');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('resolved_at');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamp('verified_at')->nullable()->after('resolved_by');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            
            $table->integer('reopen_count')->default(0)->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['resolved_at', 'resolved_by', 'verified_at', 'verified_by', 'reopen_count']);
        });
    }
};
