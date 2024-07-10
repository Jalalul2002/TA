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
        Schema::create('assetlabs', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('product_id')->constrained(
            //     table: 'products',
            //     indexName: 'aset_product'
            // );
            $table->string('product_id')->unique();
            $table->string('product_name');
            $table->string('merk')->nullable();
            $table->string('type');
            $table->integer('stock')->default(0);
            $table->string('location');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'create_asset_user'
            );
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'update_asset_user'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assetlabs');
    }
};
