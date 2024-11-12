<?php


namespace App\DTO\Profil;

class ProfilDto
{
    public function __construct(
        public string $lastName,
        public string $firstName,
        public ?string $image,
        public string $status
    ) {}
}
