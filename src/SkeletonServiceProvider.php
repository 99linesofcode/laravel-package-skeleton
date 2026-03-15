<?php

declare(strict_types=1);

namespace Lines\Skeleton;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Lines\Skeleton\App\Providers\PostServiceProvider;
use Livewire\Livewire;

class SkeletonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PostServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');

        Livewire::addNamespace(
            namespace: 'skeleton',
            classNamespace: 'Lines\\Skeleton\\App\\Livewire',
            classPath: __DIR__.'/App/Livewire',
            classViewPath: __DIR__.'/../resources/views',
        );

        // Blade::anonymousComponentPath(__DIR__.'/App/Views/Components', 'laravelpackageskeleton');
        // Blade::componentNamespace('Auth\\App\\Views\\Components', 'laravelpackageskeleton');

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);
    }
}
