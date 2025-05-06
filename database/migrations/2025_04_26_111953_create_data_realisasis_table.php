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
        Schema::create('data_realisasis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prodi');
            $table->enum('type', ['bhp',  'inventaris']);
            $table->enum('status', ['belum',  'selesai'])->default('belum');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'data_realisasis_creator'
            );
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'data_realisasis_updater'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_realisasis');
    }
};
