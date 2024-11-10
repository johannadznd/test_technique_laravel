<?php

namespace App\Services;

use App\Models\Administrator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AdministratorAuthService
{
    public function authenticate(string $email, string $password): string
    {
        $administrator = Administrator::where('email', $email)->first();

        if (!$administrator || !Hash::check($password, $administrator->password)) {
            throw new AuthenticationException('Les identifiants sont incorrects.');
        }

        return $administrator->createToken('admin-token')->plainTextToken;
    }
}
