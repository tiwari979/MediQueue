<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes — MediQueue HMS
| INT221 MVC Programming Project
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Auth Routes (only for guests — logged out users) ────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── All Protected Routes (must be logged in) ────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── OPD Queue Management ──────────────────────────────────────────────────
    Route::prefix('opd')->name('opd.')->group(function () {
        Route::get('/',                [OpdController::class, 'index'])->name('index');
        Route::get('/create',          [OpdController::class, 'create'])->name('create');
        Route::post('/store',          [OpdController::class, 'store'])->name('store');
        Route::get('/{id}',            [OpdController::class, 'show'])->name('show');
        Route::post('/{id}/call-next', [OpdController::class, 'callNext'])->name('callnext');
        Route::post('/{id}/complete',  [OpdController::class, 'complete'])->name('complete');
        Route::delete('/{id}',         [OpdController::class, 'destroy'])->name('destroy');
    });

    // ── Bed Management ────────────────────────────────────────────────────────
    Route::prefix('beds')->name('beds.')->group(function () {
        Route::get('/',              [BedController::class, 'index'])->name('index');
        Route::get('/create',        [BedController::class, 'create'])->name('create');
        Route::post('/store',        [BedController::class, 'store'])->name('store');
        Route::get('/{id}/edit',     [BedController::class, 'edit'])->name('edit');
        Route::put('/{id}',          [BedController::class, 'update'])->name('update');
        Route::post('/{id}/release', [BedController::class, 'release'])->name('release');
    });

    // ── Patient Management ────────────────────────────────────────────────────
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/',                    [PatientController::class, 'index'])->name('index');
        Route::get('/create',              [PatientController::class, 'create'])->name('create');
        Route::post('/store',              [PatientController::class, 'store'])->name('store');
        Route::get('/{id}',                [PatientController::class, 'show'])->name('show');
        Route::get('/{id}/edit',           [PatientController::class, 'edit'])->name('edit');
        Route::put('/{id}',                [PatientController::class, 'update'])->name('update');
        Route::post('/{id}/admit',         [PatientController::class, 'admit'])->name('admit');
        Route::post('/{id}/discharge',     [PatientController::class, 'discharge'])->name('discharge');
    });

    // ── Inventory Management ──────────────────────────────────────────────────
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/',               [InventoryController::class, 'index'])->name('index');
        Route::get('/create',         [InventoryController::class, 'create'])->name('create');
        Route::post('/store',         [InventoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit',      [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{id}',           [InventoryController::class, 'update'])->name('update');
        Route::post('/{id}/dispense', [InventoryController::class, 'dispense'])->name('dispense');
        Route::delete('/{id}',        [InventoryController::class, 'destroy'])->name('destroy');
    });

    // ── Reports ───────────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',          [ReportController::class, 'index'])->name('index');
        Route::get('/opd',       [ReportController::class, 'opd'])->name('opd');
        Route::get('/beds',      [ReportController::class, 'beds'])->name('beds');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    });

});