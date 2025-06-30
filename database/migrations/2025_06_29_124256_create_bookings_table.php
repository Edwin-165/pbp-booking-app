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
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel 'users'
            $table->foreignId('package_id')->constrained()->onDelete('restrict'); // Foreign key ke tabel 'packages'
            $table->date('start_date'); // Tanggal mulai sewa
            $table->date('end_date'); // Tanggal selesai sewa
            $table->decimal('total_price', 10, 2); // Total biaya sewa
            $table->enum('status', ['pending', 'confirmed', 'rented', 'completed', 'cancelled'])->default('pending'); // Status pemesanan
            $table->string('booking_code', 20)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
