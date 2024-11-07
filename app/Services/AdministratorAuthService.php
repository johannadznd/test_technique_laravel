<?php

namespace App\Services;

use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdministratorAuthService
{
    public function authenticate(string $email, string $password): string
    {
        $administrator = Administrator::where('email', $email)->first();

        if (!$administrator || !Hash::check($password, $administrator->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        return $administrator->createToken('admin-token')->plainTextToken;
    }
}
