<?php

namespace Tests\Unit;

use Src\Infrastructure\Profil\Services\ProfilService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Src\Domain\Profil\Repositories\ProfilRepositoryInterface;
use Tests\TestCase;

class ProfilServiceTest extends TestCase
{

    protected $profilService;
    protected $filesystem;
    protected $profilRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock du repository
        $this->profilRepository = $this->createMock(ProfilRepositoryInterface::class);

        // Mock du filesystem
        $this->filesystem = $this->createMock(Filesystem::class);

        // Instanciation du service avec le repository mocké et le filesystem m<ocké
        $this->profilService = new ProfilService($this->profilRepository, $this->filesystem);
    }

    /** @test */
    public function it_stores_an_image_and_returns_file_name()
    {
        // On simule une image uploadée
        $file = UploadedFile::fake()->image('test.jpg');

        // Mock pour la méthode putFileAs de Filesystem
        $this->filesystem
            ->expects($this->once())
            ->method('putFileAs')
            ->with('profiles', $file, $this->anything());

        $fileName = $this->profilService->storeImage($file);

        // Vérifie que le nom de fichier contient une extension jpg
        $this->assertStringEndsWith('.jpg', $fileName);
    }

    /** @test */
    public function it_throws_exception_when_image_cannot_be_stored()
    {
        // Simule une image uploadée
        $file = UploadedFile::fake()->image('test.jpg');

        // Mock pour forcer une exception lors de l'appel à putFileAs
        $this->filesystem
            ->expects($this->once())
            ->method('putFileAs')
            ->willThrowException(new \Exception('Erreur d\'enregistrement'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('L\'image n\'a pas pu être enregistrée.');

        $this->profilService->storeImage($file);
    }
}
