<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/partners/{partner:slug}', [PartnerController::class, 'show'])->name('partner.show');
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '(?!login|logout|livewire)[a-z0-9-]+')
    ->name('page.show');
