<?php

namespace Src\Domain\Administrator\Repositories;

use Src\Domain\Administrator\Models\Administrator;

interface AdministratorRepositoryInterface
{
    public function findByEmail(string $email): ?Administrator;
}