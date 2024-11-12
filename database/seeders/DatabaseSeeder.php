<?php

use Database\Seeders\Administrator\AdministratorSeeder;
use Database\Seeders\Profil\ProfilSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(AdministratorSeeder::class);
        $this->call(ProfilSeeder::class);
    }
}
