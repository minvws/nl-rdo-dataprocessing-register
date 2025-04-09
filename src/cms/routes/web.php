<?php

declare(strict_types=1);

use App\Filament\Pages\OneTimePasswordValidation;
use App\Http\Controllers\Authentication\PasswordlessLoginController;
use App\Http\Controllers\PrivateMediaController;
use App\Http\Controllers\RedirectToTenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', RedirectToTenantController::class);

Route::get('/media/{media}', PrivateMediaController::class)
    ->name('media.private');

Route::get('/login/consume', [PasswordlessLoginController::class, 'consume'])
    ->middleware('signed')
    ->name('passwordless-login.validate');
Route::post('/login/consume', [PasswordlessLoginController::class, 'confirm'])
    ->middleware('signed')
    ->name('passwordless-login.validate');

Route::get('/{tenant}/two-factor-authentication', OneTimePasswordValidation::class)
    ->name('two-factor-authentication');
