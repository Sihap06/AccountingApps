<?php

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Dashboard\PointOfSales;
use Illuminate\Support\Facades\Route;

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
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('index');
    Route::get('point-of-sales', PointOfSales::class)->name('point-of-sales');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});
