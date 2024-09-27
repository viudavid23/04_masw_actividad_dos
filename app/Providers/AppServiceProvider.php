<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Contracts\ActorContract;
use App\Http\Contracts\CountryContract;
use App\Http\Contracts\LanguageContract;
use App\Http\Contracts\PlatformContract;
use App\Http\Contracts\PersonContract;
use App\Http\Services\Implementations\ActorService;
use App\Http\Services\Implementations\CountryService;
use App\Http\Services\Implementations\LanguageService;
use App\Http\Services\Implementations\PlatformService;
use App\Http\Services\Implementations\PersonService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ActorContract::class, ActorService::class);
        $this->app->bind(CountryContract::class, CountryService::class);
        $this->app->bind(LanguageContract::class, LanguageService::class);
        $this->app->bind(PlatformContract::class, PlatformService::class);
        $this->app->bind(PersonContract::class, PersonService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
