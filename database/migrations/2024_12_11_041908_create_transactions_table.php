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
            $table->string('nim');
            $table->string('name');
            $table->string('telp');
            $table->string('product_code');
            $table->integer('stock');
            $table->integer('jumlah_pemakaian');
            $table->integer('updated_stock');
            $table->string('detail');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'transaction_create_user'
            );
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'transaction_update_user'
            );
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
