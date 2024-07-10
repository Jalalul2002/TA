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
            $table->string('prodi');
            $table->foreignId('product_id')->constrained(
                table: 'assetlabs',
                indexName: 'create_perencanaan_product'
            );
            $table->integer('stok');
            $table->integer('jumlah_kebutuhan');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'create_perencanaan_user'
            );
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'update_perencanaan_user'
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
