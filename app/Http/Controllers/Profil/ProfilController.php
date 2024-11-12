<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use App\Services\ProfilService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfilController extends Controller
{


    protected ProfilService $profilService;

    public function __construct(ProfilService $profilService)
    {
        $this->profilService = $profilService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return $this->profilService->getAllProfil($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la récupération des profils',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfilRequest $request)
    {
        try {

            $profilDTO = $request->toDTO();

            $imageName = $this->profilService->storeImage($request->file('image'));

            $this->profilService->createProfil($profilDTO, $imageName);

            return response()->json(
                [
                    'message' => "Le profil a bien été créé"
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la création du profil',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $profil = $this->profilService->getById($id);
            return $profil;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la récupération du profil',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfilRequest $request, string $id)
    {
        // On essaie de récupérer le profil avec find()
        $profil = Profil::find($id);

        // Si le profil n'existe pas, retourner un message d'erreur personnalisé
        if (!$profil) {
            return response()->json([
                'message' => 'Aucun profil existant pour cet id.',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Récupérer les données validées depuis le DTO
            $profilDTO = $request->toDTO();

            // Gérer l'image si elle est présente dans la requête
            if ($request->hasFile('image')) {
                $imageName = $this->profilService->storeImage($request->file('image'));
                $profil->image = $imageName;
            }

            $this->profilService->updateProfil($profilDTO, $profil);

            return response()->json([
                'message' => 'Le profil a bien été modifié.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la modification du profil.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //On essaie de récupérer le profil avec find()
        $profil = Profil::find($id);

        // Si le profil n'existe pas, retourner un message d'erreur personnalisé
        if (!$profil) {
            return response()->json([
                'message' => 'Aucun profil existant pour cet id.',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $profil->deleteOrFail();
            return response()->json([
                'message' => 'Le profil a bien été supprimé.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la suppression du profil.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
