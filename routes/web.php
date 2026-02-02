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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;


Route::get('/user', [UserController::class, 'index'])->name('user.index');

Route::prefix('user')->group(function () {
    Route::get('/bencana-data', [UserController::class, 'bencana'])->name('user.bencana');
    Route::get('/posko-data', [UserController::class, 'posko'])->name('user.posko');
    Route::get('/fasilitas-data', [UserController::class, 'fasilitas'])->name('user.fasilitas');
    Route::get('/logistik-data', [UserController::class, 'logistik'])->name('user.logistik');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

});

Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Change Password
    Route::get('/change-password', [ChangePasswordController::class, 'form'])->name('password.change.form');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');

    // Logistik Villages
    Route::get('/logistik/villages/{districtId}', [JalurDistribusiLogistikController::class, 'villagesByDistrict']);

    // Region API
    Route::prefix('api/region')->name('api.region.')->group(function () {
        Route::get('/districts', [RegionApiController::class, 'districts'])->name('districts');
        Route::get('/villages', [RegionApiController::class, 'villages'])->name('villages');
    });

    // Data Mitigasi
    Route::prefix('mitigasi')->name('mitigasi.')->group(function () {
        Route::get('/', [MitigasiController::class, 'index'])->name('index');
    });

    // Data Bencana
    Route::prefix('bencana')->name('bencana.')->group(function () {
        Route::get('/', [BencanaController::class, 'index'])->name('index');
        Route::get('/get-bencana', [BencanaController::class, 'getBencana'])->name('get_bencana');
        Route::post('/', [BencanaController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BencanaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BencanaController::class, 'update'])->name('update');
        Route::delete('/{id}', [BencanaController::class, 'destroy'])->name('destroy');
    });

    // Posko
    Route::prefix('posko')->name('posko.')->group(function () {
        Route::get('/', [PoskoController::class, 'index'])->name('index');
        Route::get('/get-posko', [PoskoController::class, 'getPosko'])->name('get_posko');
        Route::post('/', [PoskoController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PoskoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PoskoController::class, 'update'])->name('update');
        Route::delete('/{id}', [PoskoController::class, 'destroy'])->name('destroy');
    });

    // Jalur Evakuasi
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

    // Fasilitas Vital
    Route::prefix('fasilitasvital')->group(function () {
        Route::get('/', [FasilitasVitalController::class, 'index'])->name('fasilitasvital.index');
        Route::get('/create', [FasilitasVitalController::class, 'create'])->name('fasilitasvital.create');
        Route::post('/', [FasilitasVitalController::class, 'store'])->name('fasilitasvital.store');
        Route::get('/{id}/edit', [FasilitasVitalController::class, 'edit'])->name('fasilitasvital.edit');
        Route::put('/{id}', [FasilitasVitalController::class, 'update'])->name('fasilitasvital.update');
        Route::delete('/{id}', [FasilitasVitalController::class, 'destroy'])->name('fasilitasvital.destroy');
        Route::get('/get-fasilitas', [FasilitasVitalController::class, 'getFasilitas'])->name('fasilitasvital.get');
    });

    // Jalur Distribusi Logistik
    Route::prefix('jalur_distribusi_logistik')->group(function () {
        Route::get('/', [JalurDistribusiLogistikController::class, 'index'])->name('jalur_distribusi_logistik.index');
        Route::get('/create', [JalurDistribusiLogistikController::class, 'create'])->name('jalur_distribusi_logistik.create');
        Route::post('/', [JalurDistribusiLogistikController::class, 'store'])->name('jalur_distribusi_logistik.store');
        Route::get('/{id}/edit', [JalurDistribusiLogistikController::class, 'edit'])->name('jalur_distribusi_logistik.edit');
        Route::put('/{id}', [JalurDistribusiLogistikController::class, 'update'])->name('jalur_distribusi_logistik.update');
        Route::delete('/{id}', [JalurDistribusiLogistikController::class, 'destroy'])->name('jalur_distribusi_logistik.destroy');
        Route::get('/get-logistik', [JalurDistribusiLogistikController::class, 'getLogistik'])->name('jalur_distribusi_logistik.get');
    });

    // Admin Only - User Management
    Route::group(['middleware' => ['isadmin']], function () {
        // Tambahkan route khusus admin di sini jika diperlukan
    });

    // Calendar
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    // Profile
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    // Form Elements
    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    // Tables
    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    // Blank Page
    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    // Error 404
    Route::get('/error-404', function () {
        return view('pages.errors.error-404', ['title' => 'Error 404']);
    })->name('error-404');

    // Charts
    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    // UI Elements
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
});