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
        Schema::create('package_equipment', function (Blueprint $table) {
            // Foreign key ke tabel 'packages'
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            // Foreign key ke tabel 'equipment'
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('quantity'); // Jumlah unit equipment dalam paket ini

            // Menjadikan kombinasi package_id dan equipment_id sebagai primary key unik
            $table->primary(['package_id', 'equipment_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_equipment');
    }
};
