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
        // Update the enum to include 'processing' status
        DB::statement("ALTER TABLE resep MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'processing', 'completed') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'processing' from enum
        DB::statement("ALTER TABLE resep MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'completed') DEFAULT 'draft'");
    }
};
