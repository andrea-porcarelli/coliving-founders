<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        \Livewire\Livewire::setUpdateRoute(fn ($handle) => Route::post('/lw-update', $handle));
        \Livewire\Livewire::setScriptRoute(fn ($handle) => Route::get('/lw-script.js', $handle));
    }
}
