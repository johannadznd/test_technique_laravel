<?php

namespace App\Services;

use App\Dto\profilDto;
use App\Models\Profil;
use Illuminate\Contracts\Filesystem\Filesystem;
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
        $this->storage->putFileAs('profiles', $image, $imageName);
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
    public function createProfil(profilDto $profilDTO, string $imageName): Profil
    {
        return Profil::create([
            'lastName' => $profilDTO->lastName,
            'firstName' => $profilDTO->firstName,
            'image' => $imageName,
            'status' => $profilDTO->status,
        ]);
    }
}
