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
        Schema::table('resep', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('status');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->after('approved_by');
            $table->foreignId('received_by')->nullable()->constrained('users')->after('rejected_by');
            $table->timestamp('approved_at')->nullable()->after('received_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->timestamp('received_at')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['received_by']);
            $table->dropColumn(['approved_by', 'rejected_by', 'received_by', 'approved_at', 'rejected_at', 'received_at']);
        });
    }
};
