<?php

use App\Livewire\Pages\Index as PageIndex;
use App\Livewire\Pages\Edit as PageEdit;
use App\Livewire\Pages\Create as PageCreate;
use App\Livewire\Medias\Index as MediaIndex;
use App\Livewire\PageTable;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('pages')->as('page.')->group(function() {
        Route::get('/', PageIndex::class)->name('index');
        Route::get('/create', PageCreate::class)->name('create');
        Route::get('/edit/{id}', PageEdit::class)->name('edit');
    });

    Route::prefix('media')->as('media.')->group(function() {
        Route::get('/', MediaIndex::class)->name('index');
    });
});
