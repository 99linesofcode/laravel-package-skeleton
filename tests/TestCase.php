<?php

declare(strict_types=1);

namespace Linesofcode\LaravelPackageSkeleton\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Linesofcode\LaravelPackageSkeleton\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
