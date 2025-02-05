<?php

use App\Http\Controllers\API\MediaController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->as('api.')->group(function() {
    Route::prefix('pages')->as('page.')->group(function() {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::post('/store', [PageController::class, 'store'])->name('store');
        Route::get('/show/{id}', [PageController::class, 'show'])->name('show');
        Route::put('/update/{id}', [PageController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('media')->as('media.')->group(function() {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::post('/upload', [MediaController::class, 'store'])->name('upload');
        Route::delete('/delete/{id}', [MediaController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('teams')->as('team.')->group(function() {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::post('/invite', [TeamController::class, 'invite'])->name('invite');
        Route::post('/add', [TeamController::class, 'add'])->name('add');
        Route::post('/create', [TeamController::class, 'store'])->name('store');
        Route::put('/remove', [TeamController::class, 'remove'])->name('remove');
        Route::put('/update/{id}', [TeamController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TeamController::class, 'destroy'])->name('destroy');
    });
});

Route::get('public/pages/{slug}', [PageController::class, 'publicShow'])->name('api.page.public');
