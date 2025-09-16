<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sopir>
 */
class SopirFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            "nama_lengkap" => fake() -> name(), 
            "profil" => "", 
            "alamat" => "", 
            "email" => fake() -> unique() -> safeEmail(), 
            "telepon" => "+88888888888", 
            "user_id" => $user -> id
        ];
    }
}
