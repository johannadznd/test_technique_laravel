<?php

namespace App\Dto;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
