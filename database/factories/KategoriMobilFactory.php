<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriMobil>
 */
class KategoriMobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            [
                "nama_kategori" => "Bus"
            ], 
            [
                "nama_kategori" => "Alphard"
            ], 
            [
                "nama_kategori" => "Avanza"
            ], 
            [
                "nama_kategori" => "Elf"
            ], 
        ];
    }
}
