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
            // $table->id();
            // $table->foreignId('product_id')->constrained(
            //     table: 'products',
            //     indexName: 'aset_product'
            // );
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->string('formula')->nullable();
            $table->string('merk')->nullable();
            $table->string('type');
            $table->string('product_type');
            $table->integer('stock')->default(0);
            $table->string('product_unit');
            $table->string('location_detail')->nullable();
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
            $table->primary('product_code');
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
