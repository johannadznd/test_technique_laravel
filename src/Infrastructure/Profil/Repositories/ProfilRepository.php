<?php

namespace Src\Infrastructure\Profil\Repositories;

use Src\Domain\Profil\Dtos\ProfilDTO;
use Src\Domain\Profil\Models\Profil;
use Src\Domain\Profil\Repositories\ProfilRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProfilRepository implements ProfilRepositoryInterface
{
    public function findAll(Request $request): LengthAwarePaginator
    {
        $query = Profil::query();

        $isAuth = auth('administrator')->check();

        $limit = $request->input('limit', 10);

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


        return $profils;
    }

    public function create(ProfilDTO $profilDTO, string $imageName): Profil
    {
        return Profil::create([
            'lastName' => $profilDTO->lastName,
            'firstName' => $profilDTO->firstName,
            'image' => $imageName,
            'status' => $profilDTO->status,
        ]);
    }

    public function update(ProfilDTO $profilDTO, Profil $profil, ?string $imageName): Profil
    {

        $profil->lastName = $profilDTO->lastName;
        $profil->firstName = $profilDTO->firstName;
        $profil->status = $profilDTO->status;

        if ($imageName) {
            $profil->image = $imageName;
        }

        $profil->save();
        return $profil;
    }

    public function findById(string $id): ?Profil
    {
        $query = Profil::query();
        $isAuth = auth('administrator')->check();

        if (!$isAuth) {
            // Si on n'est pas authentifié, on n'affiche que les profils actifs
            $query->where('status', 'actif');
        }

        $profil = $query->where('id', $id)->first();

        return $profil;
    }

    public function delete(Profil $profil): bool
    {
        return $profil->delete();
    }
}
