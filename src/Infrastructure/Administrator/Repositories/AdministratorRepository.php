<?php

namespace Src\Infrastructure\Administrator\Repositories;

use Src\Domain\Administrator\Models\Administrator;
use Src\Domain\Administrator\Repositories\AdministratorRepositoryInterface;

class AdministratorRepository implements AdministratorRepositoryInterface
{
    public function findByEmail(string $email): ?Administrator
    {
        return Administrator::where('email', $email)->first();
    }
}
