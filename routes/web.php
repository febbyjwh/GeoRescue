<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\EvakuasiController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\FasilitasVitalController;
use App\Http\Controllers\JalurDistribusiLogistikController;
use App\Http\Controllers\MitigasiController;


// Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/', function () {
    return view('dashboard', ['title' => 'Dashboard']);
})->name('dashboard');

// data mitigasi
Route::prefix('mitigasi')->name('mitigasi.')->group(function () {
    Route::get('/', [MitigasiController::class, 'index'])->name('index');
});

// data bencana
Route::prefix('bencana')->name('bencana.')->group(function () {
    Route::get('/', [BencanaController::class, 'index'])->name('index');
});

// jalur evakuasi
Route::prefix('jalur_evakuasi')->name('jalur_evakuasi.')->group(function () {
    Route::get('/', [EvakuasiController::class, 'index'])->name('index');
    Route::get('/create', [EvakuasiController::class, 'create'])->name('create');
    Route::get('/geojson/jalur-evakuasi', [EvakuasiController::class, 'geojson']);
    Route::get('/jalur_evakuasi/{id}/geojson', [EvakuasiController::class, 'geojsonById']);
    Route::post('/', [EvakuasiController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [EvakuasiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [EvakuasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [EvakuasiController::class, 'destroy'])->name('destroy');
});

Route::group(['middleware' => ['isadmin']], function () {

    // Route Bencana
    // Route::prefix('bencana')->name('bencana.')->group(function () {
    //     Route::get('/', [BencanaController::class, 'index'])->name('index');
    // });
    Route::resource('posko', PoskoController::class)->names([
        'index' => 'posko.index',
        'create' => 'posko.create',
        'store' => 'posko.store',
        'update' => 'posko.update',
        'destroy' => 'posko.destroy',
    ]);

    Route::resource('fasilitasvital', FasilitasVitalController::class)->names([
        'index' => 'fasilitasvital.index',
        'create' => 'fasilitasvital.create',
        'store' => 'fasilitasvital.store',
        'update' => 'fasilitasvital.update',
        'destroy' => 'fasilitasvital.destroy',
    ]);

    Route::resource('jalur_distribusi_logistik', JalurDistribusiLogistikController::class)->names([
        'index' => 'jalur_distribusi_logistik.index',
        'create' => 'jalur_distribusi_logistik.create',
        'store' => 'jalur_distribusi_logistik.store',
        'update' => 'jalur_distribusi_logistik.update',
        'destroy' => 'jalur_distribusi_logistik.destroy',
    ]);
});

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');
