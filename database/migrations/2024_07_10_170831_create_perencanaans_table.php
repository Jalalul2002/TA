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
        Schema::create('perencanaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_id')->constrained(
                table: 'data_perencanaans',
                indexName: 'create_perencanaan_data'
            );
            $table->string('product_code');
            $table->foreign('product_code')->references('product_code')->on('assetlabs')->onDelete('cascade');
            // $table->foreignId('product_id')->constrained(
            //     table: 'assetlabs',
            //     indexName: 'create_perencanaan_product'
            // );
            $table->decimal('stock', 18, 4)->default(0);
            $table->bigInteger('purchase_price')->default(0);
            $table->decimal('jumlah_kebutuhan', 18, 4)->default(0);
            $table->bigInteger('total_price')->default(0);
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'rencana_create_user'
            );
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'rencana_update_user'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perencanaans');
    }
};
