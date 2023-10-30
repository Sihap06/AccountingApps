<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Livewire\Dashboard\Dashboard;
use App\Http\Livewire\Dashboard\Inventory;
use App\Http\Livewire\Dashboard\Reporting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', Dashboard::class)->name('index');
        Route::get('inventory', Inventory::class)->name('inventory');
        Route::get('point-of-sales', PointOfSales::class)->name('point-of-sales');
        Route::get('reporting', Reporting::class)->name('reporting');
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});

//BACKEND AREA
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
