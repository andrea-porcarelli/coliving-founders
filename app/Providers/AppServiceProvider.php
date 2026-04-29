<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Livewire::setUpdateRoute(fn ($handle) => Route::post('/lw-update', $handle));
        Livewire::setScriptRoute(fn ($handle) => Route::get('/lw-script.js', $handle));
    }

    public function boot(): void
    {
        //
    }
}
