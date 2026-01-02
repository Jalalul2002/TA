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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade'); // User who approves
            $table->enum('level', ['dosen', 'laboran', 'koordinator', 'kepala', 'ketua'])->default('dosen'); // Comments from the approver
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the approval
            $table->date('approval_date')->nullable(); // Date of approval
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
