<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Contracts\ActorContract;
use App\Http\Contracts\ActorSerieContract;
use App\Http\Contracts\CountryContract;
use App\Http\Contracts\DirectorContract;
use App\Http\Contracts\DirectorSerieContract;
use App\Http\Contracts\LanguageContract;
use App\Http\Contracts\LanguageSerieContract;
use App\Http\Contracts\PlatformContract;
use App\Http\Contracts\PersonContract;
use App\Http\Contracts\PlatformSerieContract;
use App\Http\Contracts\SerieContract;
use App\Http\Services\Implementations\ActorSerieService;
use App\Http\Services\Implementations\ActorService;
use App\Http\Services\Implementations\CountryService;
use App\Http\Services\Implementations\DirectorSerieService;
use App\Http\Services\Implementations\DirectorService;
use App\Http\Services\Implementations\LanguageSerieService;
use App\Http\Services\Implementations\LanguageService;
use App\Http\Services\Implementations\PlatformService;
use App\Http\Services\Implementations\PersonService;
use App\Http\Services\Implementations\PlatformSerieService;
use App\Http\Services\Implementations\SerieService;
use App\Models\Serie;
use App\Observers\SerieObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ActorSerieContract::class, ActorSerieService::class);
        $this->app->bind(ActorContract::class, ActorService::class);
        $this->app->bind(CountryContract::class, CountryService::class);
        $this->app->bind(DirectorSerieContract::class, DirectorSerieService::class);
        $this->app->bind(DirectorContract::class, DirectorService::class);
        $this->app->bind(LanguageSerieContract::class, LanguageSerieService::class);
        $this->app->bind(LanguageContract::class, LanguageService::class);
        $this->app->bind(PlatformContract::class, PlatformService::class);
        $this->app->bind(PersonContract::class, PersonService::class);
        $this->app->bind(PlatformSerieContract::class, PlatformSerieService::class);
        $this->app->bind(SerieContract::class, SerieService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Serie::observe(SerieObserver::class);
    }
}
