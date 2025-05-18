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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lab_id')->constrained('data_labs')->onDelete('cascade');
            $table->string('name');
            $table->string('prodi');
            $table->string('detail');
            $table->string('dosen')->nullable();
            $table->string('kepala_lab')->nullable();
            $table->string('ketua_lab')->nullable();
            $table->string('laboran')->nullable();
            $table->enum('status_pengajuan', ['pending', 'approved', 'rejected'])->default('pending'); // pending, approved, rejected
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
