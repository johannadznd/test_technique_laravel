<?php

namespace Tests\Feature;

use App\Models\Administrator\Administrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateProfilTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Authentifier un utilisateur pour les tests
     *
     * @return string
     */
    private function authenticate()
    {
        // Création d'un utilisateur
        $user = Administrator::factory()->create();

        // Création d'un token d'authentification pour cet utilisateur
        $token = $user->createToken('admin-token')->plainTextToken;

        // On retourner le token pour l'ajouter dans les headers de la requête
        return $token;
    }

    public function test_create_profil_requires_authentication()
    {
        //On test le cas où on n'est pas authentifié
        $response = $this->postJson('/api/profil');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_store_creates_profile_with_valid_data()
    {

        // On récupère le token
        $token = $this->authenticate();
        //On créer une requête avec des données valides
        $data = [
            'lastName' => 'Doe',
            'firstName' => 'John',
            'image' => UploadedFile::fake()->image('profile.jpg'),
            'status' => 'actif',
        ];

        //On appeler l'API pour créer le profil
        $response = $this->postJson('/api/profil', $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        //On vérifie que la réponse est 201 Created
        $response->assertStatus(Response::HTTP_CREATED);

        //On vérifier que le profil a été créé en base de données
        $this->assertDatabaseHas('profils', [
            'lastName' => 'Doe',
            'firstName' => 'John',
            'status' => 'actif',
        ]);

        //Et on vérifier que la réponse contient le message de succès
        $response->assertJson([
            'message' => 'Le profil a bien été créé',
        ]);
    }

    public function test_store_requires_lastname()
    {

        // On récupère le token
        $token = $this->authenticate();
        //On créer une requête sans le champ lastName
        $data = [
            'firstName' => 'John',
            'image' => UploadedFile::fake()->image('profile.jpg'),
            'status' => 'actif',
        ];

        //On appele l'API pour créer le profil
        $response = $this->postJson('/api/profil', $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        //On vérifie que la réponse est 422 Unprocessable Entity
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //On vérifie que l'erreur est renvoyée pour le champ lastName
        $response->assertJsonValidationErrors(['lastName']);
    }

    public function test_store_requires_valid_image()
    {

        // On récupère le token
        $token = $this->authenticate();

        //On créer une requête avec un fichier non image
        $data = [
            'lastName' => 'Doe',
            'firstName' => 'John',
            'image' => UploadedFile::fake()->create('file.txt', 1024), // Non image
            'status' => 'actif',
        ];

        //On appeler l'API pour créer le profil
        $response = $this->postJson('/api/profil', $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        //On vérifie que la réponse est 422 Unprocessable Entity
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // On vérifie que l'erreur est renvoyée pour l'image
        $response->assertJsonValidationErrors(['image']);
    }


    public function test_store_requires_valid_status()
    {

        // On récupère le token
        $token = $this->authenticate();

        // On créer une requête avec un statut invalide
        $data = [
            'lastName' => 'Doe',
            'firstName' => 'John',
            'image' => UploadedFile::fake()->image('profile.jpg'),
            'status' => 'invalid_status', // Statut invalide
        ];

        // On appele l'API pour créer le profil
        $response = $this->postJson('/api/profil', $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // On vérifie que la réponse est 422 Unprocessable Entity
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // On vérifie que l'erreur est renvoyée pour le statut
        $response->assertJsonValidationErrors(['status']);
    }
}
