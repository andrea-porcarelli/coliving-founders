<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Seo\LlmsController;
use App\Http\Controllers\Seo\RobotsController;
use App\Http\Controllers\Seo\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', SitemapController::class)->name('seo.sitemap');
Route::get('/robots.txt', RobotsController::class)->name('seo.robots');
Route::get('/llms.txt', [LlmsController::class, 'index'])->name('seo.llms');
Route::get('/llms-full.txt', [LlmsController::class, 'full'])->name('seo.llms.full');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/submissions', [\App\Http\Controllers\Admin\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [\App\Http\Controllers\Admin\SubmissionController::class, 'show'])->name('submissions.show');
    Route::delete('/submissions/{submission}', [\App\Http\Controllers\Admin\SubmissionController::class, 'destroy'])->name('submissions.destroy');
});

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/partners/{partner:slug}', [PartnerController::class, 'show'])->name('partner.show');
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '(?!login|logout|livewire|sitemap|robots|llms|admin)[a-z0-9-]+')
    ->name('page.show');
