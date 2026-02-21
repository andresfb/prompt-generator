<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\RandomPromptController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('v1')
    ->group(function () {

        Route::get('/prompt', RandomPromptController::class)
            ->name('prompt');

});
