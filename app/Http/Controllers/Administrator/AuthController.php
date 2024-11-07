<?php 


namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AdministratorAuthService;

class AuthController extends Controller
{
    protected AdministratorAuthService $authService;

    public function __construct(AdministratorAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $loginDTO = $request->toDTO();

        $token = $this->authService->authenticate($loginDTO->email, $loginDTO->password);

        return response()->json([
            'token' => $token,
            'message' => 'Connexion réussie',
        ]);
    }
}
