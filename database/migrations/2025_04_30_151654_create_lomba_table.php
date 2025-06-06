<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('lomba');
        Schema::create('lomba', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('penyelenggara');
            $table->enum('tingkat', ['Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional']);
            $table->foreignId('bidang_keahlian_id')->constrained('bidang_keahlian')->onDelete('cascade');
            $table->text('persyaratan');
            $table->integer('jumlah_peserta');
            $table->string('link_registrasi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->foreignId('periode_id')->constrained('periode')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('user')->onDelete('cascade');
            $table->enum('is_verified', ['Ditolak', 'Disetujui', 'Pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lomba');
    }
};
