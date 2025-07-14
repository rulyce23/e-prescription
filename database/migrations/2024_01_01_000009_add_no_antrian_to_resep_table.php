<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->string('no_antrian', 20)->nullable()->after('apotek_id');
        });
    }

    public function down(): void
    {
        Schema::table('resep', function (Blueprint $table) {
            $table->dropColumn('no_antrian');
        });
    }
}; 