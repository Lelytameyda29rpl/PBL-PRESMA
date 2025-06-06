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
        Schema::create('sertifikasi', function (Blueprint $table) {
            $table->id(); // sama dengan: $table->bigIncrements('id');
            $table->string('judul');
            $table->string('path');
            $table->string('kategori');
            $table->string('mahasiswa_nim');
            $table->foreign('mahasiswa_nim')
                ->references('nim')->on('mahasiswa')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps(); // otomatis membuat created_at dan updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikasi');
    }
};
