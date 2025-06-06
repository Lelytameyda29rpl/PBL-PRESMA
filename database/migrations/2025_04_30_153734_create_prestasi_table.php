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
        Schema::dropIfExists('prestasi');
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prestasi');
            $table->foreignId('lomba_id')->constrained('lomba')->onDelete('cascade');
            $table->string('file_bukti');
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak']);
            $table->text('catatan');
            $table->foreignId('created_by')->constrained('user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
