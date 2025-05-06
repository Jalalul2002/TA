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
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->enum('price_type', ['unit', 'rental', 'sample']);
            $table->bigInteger('price');
            $table->bigInteger('purchase_price')->default(0);
            $table->date('effective_date');
            $table->string('location');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'prices_create_user'
            )->onDelete('cascade');
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('assetlabs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_prices');
    }
};
