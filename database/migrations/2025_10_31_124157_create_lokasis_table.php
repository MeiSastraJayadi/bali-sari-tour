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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservasi_id'); 
            $table->string('lat_start', 20); 
            $table->string('lng_start', 20); 
            $table->string('lat_end', 20); 
            $table->string('lng_end', 20); 
            $table->foreign('reservasi_id')
                ->on('reservasis')
                ->references('id'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasis');
    }
};
