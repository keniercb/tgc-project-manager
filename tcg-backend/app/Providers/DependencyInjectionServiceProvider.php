<?php

namespace App\Providers;

use App\Repositories\ArtifactRepository;
use App\Repositories\Contracts\ArtifactRepositoryInterface;
use App\Repositories\Contracts\ModuleRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\ModuleRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Services\ArtifactService;
use App\Services\Contracts\ArtifactServiceInterface;
use App\Services\Contracts\GateValidationInterface;
use App\Services\Contracts\ModuleServiceInterface;
use App\Services\Contracts\ProjectServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\GateValidationService;
use App\Services\ModuleService;
use App\Services\ProjectService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class DependencyInjectionServiceProvider extends ServiceProvider
{
    protected array $classes = [
        ProjectRepositoryInterface::class => ProjectRepository::class,
        ProjectServiceInterface::class => ProjectService::class,
        UserRepositoryInterface::class => UserRepository::class,
        UserServiceInterface::class => UserService::class,
        ArtifactRepositoryInterface::class => ArtifactRepository::class,
        ArtifactServiceInterface::class => ArtifactService::class,
        GateValidationInterface::class => GateValidationService::class,
        ModuleRepositoryInterface::class => ModuleRepository::class,
        ModuleServiceInterface::class => ModuleService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->classes as $interface => $class) {
            $this->app->bind($interface, $class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
