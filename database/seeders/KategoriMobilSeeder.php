<?php

namespace Database\Seeders;

use App\Models\KategoriMobil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriMobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            "Bus", 
            "Alphard", 
            "Avanza", 
            "Elf"
        ]; 

        foreach($kategori as $item) {
            KategoriMobil::create([
                "nama_kategori" => $item
            ]); 
        }
    }
}
