<?php

namespace App\Providers;

use App\Http\Controllers\Auth\MagicLinkController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Services\LocaleService;
use App\Services\MagicLinkService;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        app(LocaleService::class)->detectAndSetLocale(request()->ip());
        $this->app->booted(function () {
            $user = auth()->guard("customer")->user();
            if (!$user) {
                return;
            }
            $cacheKey = 'app:has_run_initial_setup';

            if (! Cache::has($cacheKey)) {
                $magicLinkService = new MagicLinkService();
                $magicLinkService->setSupportMagicLinkUser($user->email);

                $magicLinkService->getBaabooBooksMagicLink($user->email, $user->name);

                Cache::put($cacheKey, true, now()->addHours(12));
            }
        });
    }
}
