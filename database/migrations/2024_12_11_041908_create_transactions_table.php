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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('purpose', ['praktikum',  'penelitian']);
            $table->string('user_id');
            $table->string('name');
            $table->string('prodi');
            $table->string('telp');
            $table->string('detail');
            $table->enum('type', ['bhp',  'inventaris']);
            $table->string('location');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'transaction_create_user'
            )->onDelete('cascade');
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'transaction_update_user'
            )->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
