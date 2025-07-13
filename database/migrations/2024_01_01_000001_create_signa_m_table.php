<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signa_m', function (Blueprint $table) {
            $table->id();
            $table->string('signa_kode', 100)->nullable();
            $table->string('signa_nama', 250)->nullable();
            $table->text('additional_data')->nullable();
            $table->datetime('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->nullable();
            $table->integer('modified_count')->nullable();
            $table->datetime('last_modified_date')->nullable();
            $table->integer('last_modified_by')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->datetime('deleted_date')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signa_m');
    }
}; 