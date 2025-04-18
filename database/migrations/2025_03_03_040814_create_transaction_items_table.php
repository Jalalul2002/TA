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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('product_code');
            $table->decimal('stock', 18, 4)->default(0);
            $table->bigInteger('unit_price');
            $table->decimal('jumlah_pemakaian', 18, 4)->default(0);
            $table->decimal('updated_stock', 18, 4)->default(0);
            $table->bigInteger('total_price');
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('assetlabs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
