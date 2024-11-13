<?php


namespace Src\Domain\Profil\DTOs;

class ProfilDTO
{
    public function __construct(
        public string $lastName,
        public string $firstName,
        public ?string $image,
        public string $status
    ) {}
}
