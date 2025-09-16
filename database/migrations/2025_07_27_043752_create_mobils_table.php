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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mobil', 100);
            $table->integer('tahun'); 
            $table->string('foto_mobil', 100); 
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('kategori_id'); 
            $table->foreign('owner_id')
                ->references('id')
                ->on('sopirs');
            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategori_mobils');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
