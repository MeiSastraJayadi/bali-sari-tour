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
        Schema::create('konfirmasi_jalans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_reservasi', 10)->unique();
            $table->boolean('konfirmasi_sopir'); 
            $table->boolean('konfirmasi_pelanggan');
            $table->foreign('kode_reservasi')
                ->references('kode')
                ->on('kode_reservasis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfirmasi_jalans');
    }
};
