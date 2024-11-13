<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profil\ProfilRequest;
use App\Http\Resources\Profil\ProfilResource;
use Src\Domain\Profil\Services\ProfilServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfilController extends Controller
{
    protected ProfilServiceInterface $profilService;

    public function __construct(ProfilServiceInterface $profilService)
    {
        $this->profilService = $profilService;
    }

    public function index(Request $request)
    {
        try {
            return ProfilResource::collection($this->profilService->getAllProfil($request));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la récupération des profils.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ProfilRequest $request)
    {
        try {
            $profilDTO = $request->toDTO();
            $this->profilService->createProfil($profilDTO, $request->file('image'));
            return response()->json(['message' => 'Le profil a bien été créé.'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        try {
            return new ProfilResource($this->profilService->getProfilById($id));
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(ProfilRequest $request, string $id)
    {
        try {
            $profilDTO = $request->toDTO();
            $image = $request->hasFile('image') ? $request->file('image') : null;
            $this->profilService->updateProfil($profilDTO, $id, $image);

            return response()->json(['message' => 'Le profil a bien été modifié.'], Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->profilService->deleteProfil($id);
            return response()->json(['message' => 'Le profil a bien été supprimé.'], Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
