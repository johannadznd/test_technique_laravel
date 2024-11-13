<?php

namespace Src\Domain\Profil\Repositories;

use Src\Domain\Profil\Dtos\ProfilDTO;
use Src\Domain\Profil\Models\Profil;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface ProfilRepositoryInterface
{
    public function create(ProfilDTO $profilDTO, string $imageName): Profil;

    public function update(ProfilDTO $profilDTO, Profil $profil, ?string $imageName): Profil;

    public function findAll(Request $request): LengthAwarePaginator;

    public function findById(string $id): ?Profil;

    public function delete(Profil $profil): bool;
}
