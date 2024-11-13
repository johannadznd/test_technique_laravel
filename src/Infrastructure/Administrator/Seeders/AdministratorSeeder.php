<?php

namespace Src\Infrastructure\Administrator\Seeders;

use Src\Infrastructure\Administrator\Factories\AdministratorFactory;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        AdministratorFactory::new()->count(10)->create();

    }
}
