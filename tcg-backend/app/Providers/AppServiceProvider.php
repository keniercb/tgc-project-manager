<?php

namespace App\Providers;

use App\Http\Controllers\Api\ArtifactController;
use App\Models\Artifact;
use App\Models\Module;
use App\Models\Project;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Module::observe(AuditObserver::class);
        Project::observe(AuditObserver::class);
        Artifact::observe(AuditObserver::class);
    }
}
