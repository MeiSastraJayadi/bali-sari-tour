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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string("kode_reservasi", 10)->unique();
            $table->date('tanggal_bayar');
            $table->boolean('lunas');
            $table->integer('biaya');
            $table->string('metode', 30);
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
        Schema::dropIfExists('invoices');
    }
};
