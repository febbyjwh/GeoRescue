<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\EvakuasiController;
use App\Http\Controllers\MitigasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegionApiController;
use App\Http\Controllers\FasilitasVitalController;
use App\Http\Controllers\JalurDistribusiLogistikController;
use App\Http\Controllers\UserController;

Route::get(
    '/jalur_distribusi_logistik/geojson',
    [JalurDistribusiLogistikController::class, 'geojson']
);

Route::get('/logistik/villages/{districtId}', [JalurDistribusiLogistikController::class, 'villagesByDistrict']);

// dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


//region api
Route::prefix('api/region')->name('api.region.')->group(function () {
    Route::get('/districts', [RegionApiController::class, 'districts'])->name('districts');
    Route::get('/villages', [RegionApiController::class, 'villages'])->name('villages');
});

Route::get('/user/bencana-data', [UserController::class, 'bencana']);
Route::get('/user/posko-data', [UserController::class, 'posko']);

// data mitigasi
Route::prefix('mitigasi')->name('mitigasi.')->group(function () {
    Route::get('/', [MitigasiController::class, 'index'])->name('index');
});

// data bencana
Route::prefix('bencana')->name('bencana.')->group(function () {
    Route::get('/', [BencanaController::class, 'index'])->name('index');
    Route::get('/get-bencana', [BencanaController::class, 'getBencana'])->name('get_bencana');
    Route::post('/', [BencanaController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BencanaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BencanaController::class, 'update'])->name('update');
    Route::delete('/{id}', [BencanaController::class, 'destroy'])->name('destroy');
});

Route::prefix('posko')->name('posko.')->group(function () {
    Route::get('/', [PoskoController::class, 'index'])->name('index');
    Route::get('/get-posko', [PoskoController::class, 'getPosko'])->name('get_posko');
    Route::post('/', [PoskoController::class, 'store'])->name('store');
    Route::put('/{id}', [PoskoController::class, 'update'])->name('update');
    Route::delete('/{id}', [PoskoController::class, 'destroy'])->name('destroy');
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

// user
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
});

// Posko
Route::group(['middleware' => ['isadmin']], function () {
// Fasilitas vital
    Route::prefix('fasilitasvital')->group(function () {
        Route::get('/', [FasilitasVitalController::class, 'index'])->name('fasilitasvital.index');
        Route::get('/create', [FasilitasVitalController::class, 'create'])->name('fasilitasvital.create');
        Route::post('/', [FasilitasVitalController::class, 'store'])->name('fasilitasvital.store');
        Route::get('/{id}/edit', [FasilitasVitalController::class, 'edit'])->name('fasilitasvital.edit');
        Route::put('/{id}', [FasilitasVitalController::class, 'update'])->name('fasilitasvital.update');
        Route::delete('/{id}', [FasilitasVitalController::class, 'destroy'])->name('fasilitasvital.destroy');
        Route::get('/get-fasilitas', [FasilitasVitalController::class, 'getFasilitas'])->name('fasilitasvital.get');
    });

// Jalur distribusi logistik
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
