<?php

namespace App\Services;

use App\DTO\ProfilDto;
use App\Http\Resources\ProfilResource;
use App\Models\Profil;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class ProfilService
{
    protected Filesystem $storage;

    public function __construct(Filesystem $storage)
    {
        $this->storage = $storage;
    }

    /**
     *  On stocke l'image et retourne le nom du fichier
     */
    public function storeImage(UploadedFile $image): string
    {
        $imageName = $this->generateImageName($image);

        try {
            $this->storage->putFileAs('profiles', $image, $imageName);
        } catch (\Exception $e) {
            throw new \RuntimeException('L\'image n\'a pas pu être enregistrée.');
        }

        return $imageName;
    }

    /**
     * On génère un nom d'image unique
     */
    private function generateImageName(UploadedFile $image): string
    {
        return uniqid() . '.' . $image->getClientOriginalExtension();
    }

    /**
     * Crée un profil avec les données DTO
     */
    public function createProfil(ProfilDto $profilDTO, string $imageName): Profil
    {
        return Profil::create([
            'lastName' => $profilDTO->lastName,
            'firstName' => $profilDTO->firstName,
            'image' => $imageName,
            'status' => $profilDTO->status,
        ]);
    }

    public function updateProfil(ProfilDto $profilDTO, Profil $profil)
    {
        // Mise à jour des autres champs
        $profil->lastName = $profilDTO->lastName;
        $profil->firstName = $profilDTO->firstName;
        $profil->status = $profilDTO->status;

        $profil->updateOrFail();
    }

    public function getAllProfil(Request $request): AnonymousResourceCollection
    {
        $isAuth = auth('administrator')->check();

        $limit = $request->input('limit', 10);

        // On prépare la requête de base
        $query = Profil::query();

        // Si on n'est pas authentifié, on n'affiche que les profils actifs
        if (!$isAuth) {
            $query->where('status', 'actif');
        }

        // On ajoute un systeme de filtre sur le nom prénom et status
        if ($request->has('lastName')) {
            $query->where('lastName', 'like', '%' . $request->input('lastName') . '%');
        }
        if ($request->has('firstName')) {
            $query->where('firstName', 'like', '%' . $request->input('firstName') . '%');
        }
        if ($request->has('status') && $isAuth) {
            $query->where('status', '=',  $request->input('status'));
        }

        // On tri par la date de création
        $query->orderBy('created_at', 'desc');

        // Et enfin on pagine tout ça
        $profils = $query->paginate($limit)
            ->appends($request->query());

        return ProfilResource::collection($profils);
    }

    public function getById(string $id)
    {
        $query = Profil::query();
        $isAuth = auth('administrator')->check();
        
        if (!$isAuth) {
            // Si on n'est pas authentifié, on n'affiche que les profils actifs
            $query->where('status', 'actif');
        }

        $profil = $query->where('id', $id)->first();

        if (!$profil) {
            return response()->json([
                'message' => 'Aucun profil existant pour cet id.',
            ], Response::HTTP_NOT_FOUND);
        }

        return new ProfilResource($profil);
    }
}
