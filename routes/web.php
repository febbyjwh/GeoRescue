<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BencanaController;

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::group(['middleware' => ['role:admin']], function (){

    // Route Bencana
    Route::prefix('Bencana')->name('Bencana.')->group(function () {
        Route::get('/Bencana', [BencanaController::class, 'index'])->name('index');
    });

});