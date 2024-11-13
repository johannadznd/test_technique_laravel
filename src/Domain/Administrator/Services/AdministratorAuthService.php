<?php

namespace Src\Domain\Administrator\Services;

use Src\Domain\Administrator\Repositories\AdministratorRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AdministratorAuthService
{
    protected AdministratorRepositoryInterface $repository;

    public function __construct(AdministratorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate(string $email, string $password): string
    {
        $administrator = $this->repository->findByEmail($email);

        if (!$administrator || !Hash::check($password, $administrator->password)) {
            throw new AuthenticationException('Les identifiants sont incorrects.');
        }

        return $administrator->createToken('admin-token')->plainTextToken;
    }
}

