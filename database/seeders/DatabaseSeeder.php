<?php

use Src\Infrastructure\Administrator\Seeders\AdministratorSeeder;
use Src\Infrastructure\Profil\Seeders\ProfilSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdministratorSeeder::class);
        $this->call(ProfilSeeder::class);
    }
}
