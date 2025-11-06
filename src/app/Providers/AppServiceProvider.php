<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // ローカルだけでOK
        if (app()->environment('local')) {
            URL::forceRootUrl(config('app.url'));  // APP_URL を強制的に使う
            URL::forceScheme('http');              // http を強制（https を使っているなら 'https'）
        }
    }
}
