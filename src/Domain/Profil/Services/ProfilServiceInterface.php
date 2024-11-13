<?php

namespace Src\Domain\Profil\Services;


use Src\Domain\Profil\Dtos\ProfilDTO;
use Src\Domain\Profil\Models\Profil;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface ProfilServiceInterface
{
    public function createProfil(ProfilDTO $profilDTO, UploadedFile $image): Profil;

    public function updateProfil(ProfilDTO $profilDTO, string $id, ?UploadedFile $image);

    public function getAllProfil(Request $request): LengthAwarePaginator;

    public function getProfilById(string $id) : Profil;

    public function deleteProfil(string $id);
}
