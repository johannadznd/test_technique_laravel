<?php

namespace Database\Seeders\Administrator;

use App\Models\Administrator\Administrator;
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
