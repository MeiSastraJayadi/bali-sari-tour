<?php

namespace Database\Factories;

use App\Models\KategoriMobil;
use App\Models\Sopir;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mobil>
 */
class MobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = Sopir::inRandomOrder() -> first();
        $kategori = KategoriMobil::where('nama_kategori', 'Avanza') -> first();  

        return [
            "nama_mobil" => "Avanza 2020", 
            "tahun" => 2020,
            "owner_id" => $owner -> id,
            "foto_mobil" => "/images/avanza.png",
            "kategori_id" => $kategori -> id
        ];
    }
}
