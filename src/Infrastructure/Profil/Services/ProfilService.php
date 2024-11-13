<?php

namespace Src\Infrastructure\Profil\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Src\Domain\Profil\Dtos\ProfilDTO;
use Src\Domain\Profil\Models\Profil;
use Src\Domain\Profil\Repositories\ProfilRepositoryInterface;
use Src\Domain\Profil\Services\ProfilServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfilService implements ProfilServiceInterface
{
    protected ProfilRepositoryInterface $profilRepository;

    protected Filesystem $storage;

    public function __construct(ProfilRepositoryInterface $profilRepository, Filesystem $storage)
    {
        $this->profilRepository = $profilRepository;
        $this->storage = $storage;

    }

    public function getAllProfil(Request $request): LengthAwarePaginator
    {
        return $this->profilRepository->findAll($request);
    }

    public function createProfil(ProfilDTO $profilDTO, UploadedFile $image): Profil
    {
        $imageName = $this->storeImage($image);
        return $this->profilRepository->create($profilDTO, $imageName);
    }

    public function updateProfil(ProfilDTO $profilDTO, string $id, ?UploadedFile $image): Profil
    {
        // Récupérer le profil à modifier
        $profil = $this->profilRepository->findById($id);

        if (!$profil) {
            throw new NotFoundHttpException("Aucun profil existant pour cet id.");
        }

        if ($image) {
            $imageName = $this->storeImage($image);
        }
        return $this->profilRepository->update($profilDTO, $profil, $imageName ?? null);
    }

    public function getProfilById(string $id): Profil
    {
        // Récupérer le profil par ID
        $profil = $this->profilRepository->findById($id);

        if (!$profil) {
            throw new NotFoundHttpException("Aucun profil existant pour cet id.");
        }

        return $profil;
    }

    public function deleteProfil(string $id): bool
    {
        // Vérifier si le profil existe avant de supprimer
        $profil = $this->profilRepository->findById($id);

        if (!$profil) {
            throw new NotFoundHttpException("Aucun profil existant pour cet id.");
        }

        return $this->profilRepository->delete($profil);
    }

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

    private function generateImageName(UploadedFile $image): string
    {
        return uniqid() . '.' . $image->getClientOriginalExtension();
    }
}
