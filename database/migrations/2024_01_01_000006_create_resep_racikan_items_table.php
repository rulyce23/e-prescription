<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('resep_racikan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('racikan_id')->constrained('resep_racikan')->onDelete('cascade');
            $table->foreignId('obatalkes_id')->constrained('obatalkes_m')->onDelete('cascade');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resep_racikan_items');
    }
}; 