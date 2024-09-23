<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Contracts\LanguageContract;
use App\Http\Contracts\PlatformContract;
use App\Http\Services\Implementations\LanguageService;
use App\Http\Services\Implementations\PlatformService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LanguageContract::class, LanguageService::class);
        $this->app->bind(PlatformContract::class, PlatformService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
