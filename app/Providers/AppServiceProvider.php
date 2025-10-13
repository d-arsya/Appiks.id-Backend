<?php

namespace App\Providers;

use App\Models\Cloud;
use App\Models\User;
use App\Observers\CloudObserver;
use App\Observers\UserObserver;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Scramble::ignoreDefaultRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
        Gate::define('dashboard-data', function (User $user) {
            return $user->role != 'student';
        });
        User::observe(UserObserver::class);
        Cloud::observe(CloudObserver::class);
    }
}
