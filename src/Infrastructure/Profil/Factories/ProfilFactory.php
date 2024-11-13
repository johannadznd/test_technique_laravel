<?php

namespace Src\Infrastructure\Profil\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Domain\Profil\Models\Profil;

class ProfilFactory extends Factory
{


    protected $model = Profil::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lastName' => $this->faker->lastName(),
            'firstName' => $this->faker->firstName(),
            'image' => $this->faker->imageUrl(640, 480),
            'status' => $this->faker->randomElement(['inactif', 'en_attente', 'actif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
