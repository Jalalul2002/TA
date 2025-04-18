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
        Schema::create('data_prediksis', function (Blueprint $table) {
            $table->id();
            $table->string("tahun_perencanaan");
            $table->string("product_code");
            $table->string("location");
            $table->decimal('kebutuhan', 18, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_prediksis');
    }
};
