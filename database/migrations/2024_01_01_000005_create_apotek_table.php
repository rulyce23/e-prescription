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
        Schema::create('apotek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_apotek');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->string('email')->nullable();
            $table->string('pemilik')->nullable();
            $table->string('sipa')->nullable(); // Surat Izin Apotek
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apotek');
    }
}; 