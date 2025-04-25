<?php

declare(strict_types=1);

namespace Linesofcode\LaravelPackageSkeleton;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Livewire\Volt\Volt;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services
     */
    public function register(): void {}

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // register any commands here
            ]);
        }

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/Migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/App/Views', 'laravelpackageskeleton');

        Blade::anonymousComponentPath(__DIR__.'/App/Views/Components', 'laravelpackageskeleton');
        Blade::componentNamespace('Auth\\App\\Views\\Components', 'laravelpackageskeleton');

        Volt::mount([
            __DIR__.'/App/Livewire',
        ]);
    }
}
