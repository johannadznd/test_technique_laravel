<?php

namespace Tests\Unit;

use Src\Domain\Administrator\Models\Administrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Src\Infrastructure\Administrator\Factories\AdministratorFactory;
use Tests\TestCase;

class AdministratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_login_with_valid_credentials()
    {
        // Création d'un administrateur pour tester

        $admin = AdministratorFactory::new()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Envoie d'une requête POST de connexion
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Vérification de la réponse
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['token', 'message']);
    }

    public function test_administrator_cannot_login_with_invalid_password()
    {
        $admin = AdministratorFactory::new()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // On essaie de se connecter avec un mauvais mot de passe
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        // Vérification de la réponse
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Les identifiants sont incorrects'
        ]);    }

    public function test_login_requires_email_and_password()
    {
        // On essaie de se connecter sans mot de passe
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
        ]);

        // On vérifie que la réponse contient une erreur de validation
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_login_fails_with_invalid_email_format()
    {
        //On test avec un mot de passe invalide
        $response = $this->postJson('/api/admin/login', [
            'email' => 'not-an-email',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email']);
    }
}
