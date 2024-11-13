<?php

namespace Tests\Feature;

use Src\Domain\Profil\Models\Profil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Src\Infrastructure\Administrator\Factories\AdministratorFactory;
use Src\Infrastructure\Profil\Factories\ProfilFactory;
use Tests\TestCase;

class GetAllProfilTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Création de profils avec différents statuts
        ProfilFactory::new()->count(3)->create(['status' => 'actif']);
        ProfilFactory::new()->count(5)->create(['status' => 'inactif']);
        ProfilFactory::new()->count(5)->create(['status' => 'en_attente']);
    }

    /**
     * Authentifier un utilisateur pour les tests
     *
     * @return string
     */
    private function authenticate()
    {
        // Création d'un utilisateur
        $user = AdministratorFactory::new()->create();

        // Création d'un token d'authentification pour cet utilisateur
        $token = $user->createToken('admin-token')->plainTextToken;

        // On retourner le token pour l'ajouter dans les headers de la requête
        return $token;
    }

    public function test_can_get_active_profils_without_authentication()
    {
        //On test qu'un utilisateur qui n'est pas authentifié n'a pas les profils inactif et en_attente (il n'y a que 3 profil actif)
        $response = $this->getJson('/api/profil');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');

        foreach ($response->json('data') as $profil) {
            $this->assertArrayNotHasKey('status', $profil); // On vérifie que 'status' n'est pas présent
        }
    }

    public function test_can_get_all_profils_with_authentication()
    {

        //On test qu'un utilisateur authentifié retourne bien le status et récupère tous les profils
        $token = $this->authenticate();
        $response = $this->getJson('/api/profil', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(10, 'data');

        foreach ($response->json('data') as $profil) {
            $this->assertArrayHasKey('status', $profil); // On vérifie que 'status' est présent
        }
    }

    public function test_can_filter_profils_by_last_name()
    {
        // Profil avec un nom spécifique pour le test de filtre
        ProfilFactory::new()->create(['lastName' => 'TestLastName', 'status' => 'actif']);

        $response = $this->getJson('/api/profil?lastName=TestLastName');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['lastName' => 'TestLastName']);
    }

    public function test_can_paginate_profils()
    {
        // On simule une pagination avec une limite de 3 profils par page
        $token = $this->authenticate();

        $response = $this->getJson('/api/profil?limit=3', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data'); // 3 profils dans la page de résultats
        $response->assertJsonStructure([
            'data',
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'last_page',
                'from',
                'to',
                'total',
            ],
        ]);
    }
}
