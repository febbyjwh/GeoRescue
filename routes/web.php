<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\PoskoController;

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::group(['middleware' => ['isadmin']], function (){

    // Route Bencana
    Route::prefix('bencana')->name('bencana.')->group(function () {
        Route::get('/', [BencanaController::class, 'index'])->name('index');
    });

    Route::resource('posko', PoskoController::class)->names([
        'index' => 'posko.index',
        'create' => 'posko.create',
        'store' => 'posko.store',
        'update' => 'posko.update',
        'destroy' => 'posko.destroy',
    ]);
});