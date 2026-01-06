<?php
use App\Http\Controllers\UserController;

Route::get('bencana', [UserController::class, 'bencana']);
Route::get('posko', [UserController::class, 'posko']);