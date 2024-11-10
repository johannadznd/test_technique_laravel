<?php


namespace App\Dto;

class profilDto
{
    public function __construct(
        public string $lastName,
        public string $firstName,
        public string $image,
        public string $status
    ) {}
}
