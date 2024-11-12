<?php

namespace Database\Seeders\Profil;

use App\Models\Profil\Profil;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profil::factory()->count(20)->create();
    }
}
