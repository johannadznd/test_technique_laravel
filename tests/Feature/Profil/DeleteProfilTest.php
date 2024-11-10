<?php

namespace Tests\Feature;

use App\Models\Administrator;
use App\Models\Profil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteProfilTest extends TestCase
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
        $response = $this->deleteJson('/api/profil/1');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteProfil()
    {
        // On récupère le token
        $token = $this->authenticate();

        // On crée un profil fictif pour tester la suppression
        $profil = Profil::factory()->create();

        // On appelle la méthode de suppression
        // On tente de supprimer un profil inexistant
        $response = $this->deleteJson('api/profil/' . $profil->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        // On vérifie que le profil a bien été supprimé
        $this->assertDatabaseMissing('profils', ['id' => $profil->id]);

        // On vérifie la réponse HTTP
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Le profil a bien été supprimé.',
        ]);
    }

    public function testDeleteProfilNotFound()
    {

        // On récupère le token
        $token = $this->authenticate();

        // On tente de supprimer un profil inexistant
        $response = $this->deleteJson('api/profil/999', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        // On vérifie la réponse pour un profil non trouvé
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Aucun profil existant pour cet id.',
        ]);
    }
}
