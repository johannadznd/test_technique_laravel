<?php

namespace Tests\Feature;

use App\Models\Administrator\Administrator;
use App\Models\Profil\Profil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateProfilTest extends TestCase
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

    public function test_delete_profil_requires_authentication()
    {
        //On test le cas où on n'est pas authentifié
        $response = $this->putJson('/api/profil/1');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateProfil()
    {
        // On récupère le token d'authentification
        $token = $this->authenticate();

        // On crée un profil fictif pour tester la mise à jour
        $profil = Profil::factory()->create();

        // On prépare les données pour la mise à jour
        $data = [
            'lastName' => 'NouveauNom',
            'firstName' => 'NouveauPrenom',
            'status' => 'actif',
            // Ajoute éventuellement un fichier image si nécessaire
            'image' => null,
        ];

        // On effectue la requête PUT pour la mise à jour du profil
        $response = $this->putJson('api/profil/' . $profil->id, $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // On vérifie la réponse HTTP
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Le profil a bien été modifié.',
        ]);

        // On vérifie que les données ont bien été mises à jour dans la base
        $this->assertDatabaseHas('profils', [
            'id' => $profil->id,
            'lastName' => 'NouveauNom',
            'firstName' => 'NouveauPrenom',
            'status' => 'actif',
        ]);
    }

    public function testUpdateProfilNotFound()
    {
        // On récupère le token d'authentification
        $token = $this->authenticate();

        // On tente de mettre à jour un profil inexistant
        $data = [
            'lastName' => 'NomInexistant',
            'firstName' => 'PrenomInexistant',
            'status' => 'actif',
        ];

        // On effectue la requête PUT pour un profil inexistant
        $response = $this->putJson('api/profil/9999', $data, [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // On vérifie la réponse pour un profil non trouvé
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Aucun profil existant pour cet id.',
        ]);
    }
}
