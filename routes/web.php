<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarkPromptUsedController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', HomeController::class)
        ->name('home');

    Route::post('/mark-used', MarkPromptUsedController::class)
        ->name('mark-used');
});
