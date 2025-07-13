<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('resep_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_id')->constrained('resep')->onDelete('cascade');
            $table->foreignId('obatalkes_id')->constrained('obatalkes_m')->onDelete('cascade');
            $table->foreignId('signa_m_id')->constrained('signa_m')->onDelete('cascade');
            $table->integer('qty');
            $table->string('aturan_pakai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resep_items');
    }
}; 