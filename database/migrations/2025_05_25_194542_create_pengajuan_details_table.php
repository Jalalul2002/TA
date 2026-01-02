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
        Schema::create('pengajuan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');
            $table->enum('item_type', ['room', 'aset', 'bhp'])->default('room'); // Example item types
            $table->string('item_id')->nullable(); // item
            $table->string('item_name'); // Name of the item
            $table->decimal('quantity', 18, 4)->default(0);
            $table->string('detail')->nullable(); // Additional details about the item
            $table->string('hari')->nullable(); // Day of the week
            $table->string('jam_mulai')->nullable(); // Time slot
            $table->string('jam_selesai')->nullable(); // End time of the slot
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_details');
    }
};
