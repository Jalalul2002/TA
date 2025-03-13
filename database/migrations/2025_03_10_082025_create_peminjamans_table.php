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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('product_code');
            $table->foreign('product_code')->references('product_code')->on('assetlabs')->onDelete('cascade');
            $table->integer('stock');
            $table->integer('quantity');
            $table->integer('returned_quantity')->nullable()->default(0);
            $table->integer('damaged_quantity')->nullable()->default(0);
            $table->date('loan_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan sebagian', 'dikembalikan', 'rusak'])->default('dipinjam');
            $table->text('notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'peminjaman_create_user'
            )->onDelete('cascade');
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'peminjaman_update_user'
            )->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
