<?php


namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\LoginRequest;
use App\Services\Administrator\AdministratorAuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected AdministratorAuthService $authService;

    public function __construct(AdministratorAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {

        try {
            $loginDTO = $request->toDTO();

            $token = $this->authService->authenticate($loginDTO->email, $loginDTO->password);

            return response()->json([
                'token' => $token,
                'message' => 'Connexion réussie',
            ]);
        }catch(AuthenticationException $e){
            return response()->json([
                'message' => 'Les identifiants sont incorrects',
            ], Response::HTTP_UNAUTHORIZED);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
