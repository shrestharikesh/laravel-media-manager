<?php

namespace Shrestharikesh\LaravelMediaManager;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerMigrations();
        $this->registerResources();
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-media-manager');
        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/laravel-media-manager'),
        ], 'laravel-media-manager-views');
    }
}
