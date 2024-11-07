<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrator::factory()->count(10)->create();
    }
}
