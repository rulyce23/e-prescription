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
            $table->foreignId('apotek_id')->nullable()->constrained('apotek')->onDelete('set null');
            $table->date('tgl_pengajuan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->dropForeign(['apotek_id']);
            $table->dropColumn(['apotek_id', 'tgl_pengajuan']);
        });
    }
}; 