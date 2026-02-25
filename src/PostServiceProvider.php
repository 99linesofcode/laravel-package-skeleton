<?php

declare(strict_types=1);

namespace Lines\Skeleton;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PostServiceProvider extends PanelProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'../database/migrations');

        // Blade::anonymousComponentPath(__DIR__.'/App/Views/Components', 'laravelpackageskeleton');
        // Blade::componentNamespace('Auth\\App\\Views\\Components', 'laravelpackageskeleton');

        // $this->mergeConfigFrom([
        //     __DIR__.'/../config/blog.php', 'blog',
        // ]);

        $this->publishesMigrations([
            __DIR__.'../database/migrations' => database_path('migrations'),
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->discoverResources(in: __DIR__.'/App/Filament/Resources', for: 'Lines\Skeleton\App\Filament\Resources')
            ->discoverPages(in: __DIR__.'/App/Filament/Pages', for: 'Lines\Skeleton\App\Filament\Pages')
            ->discoverwidgets(in: __DIR__.'/App/Filament/Widgets', for: 'Lines\Skeleton\App\Filament\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                accountwidget::class,
                filamentinfowidget::class,
            ])
            ->middleware([
                encryptcookies::class,
                addqueuedcookiestoresponse::class,
                startsession::class,
                authenticatesession::class,
                shareerrorsfromsession::class,
                verifycsrftoken::class,
                substitutebindings::class,
                disablebladeiconcomponents::class,
                dispatchservingfilamentevent::class,
            ])
            ->authmiddleware([
                authenticate::class,
            ]);
    }
}
