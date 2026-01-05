<?php
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\PoskoController;

Route::get('/bencana', [BencanaController::class, 'getBencana']);
Route::get('/posko', [PoskoController::class, 'getPosko']);