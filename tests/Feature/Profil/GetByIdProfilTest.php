<?php

namespace Tests\Feature;

use Src\Domain\Profil\Models\Profil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Src\Infrastructure\Administrator\Factories\AdministratorFactory;
use Src\Infrastructure\Profil\Factories\ProfilFactory;
use Tests\TestCase;

class GetByIdProfilTest extends TestCase
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
        $user = AdministratorFactory::new()->create();

        // Création d'un token d'authentification pour cet utilisateur
        $token = $user->createToken('admin-token')->plainTextToken;

        // On retourner le token pour l'ajouter dans les headers de la requête
        return $token;
    }


    public function test_get_profile_by_id_with_authentication()
    {
        $profil = ProfilFactory::new()->create();

        $token = $this->authenticate();

        $response = $this->getJson('/api/profil/' . $profil->id, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        // On vérifie que le profil retourne bien le status
        $response->assertJsonFragment([
            'id' => $profil->id,
            'status' =>  $profil->status == "en_attente" ? "en attente" : $profil->status
        ]);
    }

    public function test_get_profile_by_id_without_authentication()
    {
        $profil = ProfilFactory::new()->create(['status' => 'actif']);

        $response = $this->getJson('/api/profil/' . $profil->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'id' => $profil->id,
        ]);

        // Récupère les données du profil depuis la réponse JSON
        $data = $response->json('data');

        // Vérifie que le champ 'status' est manquant dans la réponse pour les utilisateurs non authentifiés
        $this->assertArrayNotHasKey('status', $data);
    }

    public function test_get_profile_by_id_404_if_not_found()
    {
        // On vérifie que dans le cas ou on ne trouve pas de profil on renvoie bien une 404
        $response = $this->getJson('/api/profil/999');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Aucun profil existant pour cet id.',
        ]);
    }
}
