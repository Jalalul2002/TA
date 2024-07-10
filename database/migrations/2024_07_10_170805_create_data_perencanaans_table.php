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
        Schema::create('data_perencanaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perencanaan');
            $table->string('prodi');
            $table->foreignId('created_by')->constrained(
                table: 'users',
                indexName: 'data_create_user'
            );
            $table->foreignId('updated_by')->constrained(
                table: 'users',
                indexName: 'data_update_user'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_perencanaans');
    }
};
