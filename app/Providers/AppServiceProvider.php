<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Administrator\Repositories\AdministratorRepositoryInterface;
use Src\Domain\Profil\Repositories\ProfilRepositoryInterface;
use Src\Domain\Profil\Services\ProfilServiceInterface;
use Src\Infrastructure\Administrator\Repositories\AdministratorRepository;
use Src\Infrastructure\Profil\Repositories\ProfilRepository;
use Src\Infrastructure\Profil\Services\ProfilService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // On associe les interfaces aux implémentations concrètes afin que Laravel puisse résoudre automatiquement les dépendances
        $this->app->bind(ProfilServiceInterface::class, ProfilService::class);
        $this->app->bind(ProfilRepositoryInterface::class, ProfilRepository::class);
        $this->app->bind(AdministratorRepositoryInterface::class, AdministratorRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
