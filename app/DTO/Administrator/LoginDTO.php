<?php

namespace App\DTO\Administrator;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
