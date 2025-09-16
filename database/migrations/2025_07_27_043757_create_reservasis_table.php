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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->boolean('divalidasi'); 
            $table->unsignedBigInteger('mobil_id');
            $table->unsignedBigInteger('pelanggan_id'); 
            $table->string('destinasi', 200); 
            $table->string('alamat', 300); 
            $table->integer('biaya');
            $table->string('nama'); 
            $table->string('email'); 
            $table->string('telepon'); 
            $table->integer('pax'); 
            $table->string('note', 512); 
            $table->foreign('mobil_id')
                ->on('mobils')
                ->references('id'); 
            $table->foreign('pelanggan_id')
                ->on('pelanggans')
                ->references('id'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
