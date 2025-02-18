<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Support\Facades\Http;

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
       

        $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\InjectTokenMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('api', \App\Http\Middleware\InjectTokenMiddleware::class);
        Route::get('/api/map-token', function () {
            return response()->json(['apiKey' => config('aws.map_api_key')]);
        });
       
       
        $this->loadRoutesFrom(base_path('routes/api.php'));
        
    }
}
