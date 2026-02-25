<?php

declare(strict_types=1);

namespace Lines\Skeleton;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SkeletonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        // $this->loadViewsFrom(__DIR__.'../resources/views', 'skeleton');

        // Blade::anonymousComponentPath(__DIR__.'/App/Views/Components', 'laravelpackageskeleton');
        // Blade::componentNamespace('Auth\\App\\Views\\Components', 'laravelpackageskeleton');

        // $this->mergeConfigFrom([
        //     __DIR__.'/../config/blog.php', 'blog',
        // ]);

        $this->publishesMigrations([
            __DIR__.'../database/migrations' => database_path('migrations'),
        ]);
    }
}
