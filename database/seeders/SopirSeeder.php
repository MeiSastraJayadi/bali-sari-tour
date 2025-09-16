<?php

namespace Database\Seeders;

use App\Models\Sopir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SopirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sopir::factory()->count(2)->create(); 
    }
}
