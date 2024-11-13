<?php

namespace Src\Infrastructure\Profil\Seeders;

use Illuminate\Database\Seeder;
use Src\Infrastructure\Profil\Factories\ProfilFactory;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfilFactory::new()->count(20)->create();

    }
}
