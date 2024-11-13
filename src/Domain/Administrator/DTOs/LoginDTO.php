<?php

namespace Src\Domain\Administrator\DTOs;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
