<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Livewire::setUpdateRoute(function ($handle) {
            $existing = Route::getRoutes()->getByName('livewire.update');
            return $existing ?: Route::post('/lw-update', $handle);
        });
    }
}
