<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrintReceiptController;
use App\Http\Controllers\TransactionController;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Dashboard\Customers;
use App\Http\Livewire\Dashboard\Dashboard;
use App\Http\Livewire\Dashboard\Inventory;
use App\Http\Livewire\Dashboard\Inventory\Create;
use App\Http\Livewire\Dashboard\Inventory\Edit;
use App\Http\Livewire\Dashboard\LogActivity;
use App\Http\Livewire\Dashboard\PointOfSales;
use App\Http\Livewire\Dashboard\Reporting;
use App\Http\Livewire\Dashboard\Reporting\Transaction;
use App\Http\Livewire\Dashboard\TabOnInventory;
use App\Http\Livewire\Dashboard\TabOnLogActivity;
use App\Http\Livewire\Dashboard\TabOnPos;
use App\Http\Livewire\Dashboard\Teknisi;
use App\Http\Livewire\Dashboard\Verification;
use App\Http\Livewire\Dashboard\UserManagement;
use App\Http\Livewire\Dashboard\PaymentMethods;
use Illuminate\Support\Facades\Http;

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

    Route::get('/proxy/print/{transaction_id}', function ($transaction_id) {
        $url = "http://localhost:3000/print/" . $transaction_id;
        $response = Http::get($url);
        return $response->body();
    });

    Route::get('receipt/{id}/{payment_method}', [PrintReceiptController::class, 'printReceipt']);

    Route::get('test', [PrintReceiptController::class, 'generateReceipt']);

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', Dashboard::class)->name('index');
        Route::get('point-of-sales', TabOnPos::class)->name('point-of-sales');
        Route::get('reporting', Reporting::class)->name('reporting');
        Route::get('reporting/{type}', Reporting::class);
        Route::get('detail-transaction/{id}', Reporting::class);

        Route::prefix('inventory')->name('inventory')->group(function () {
            Route::get('/', TabOnInventory::class);
        });

        Route::prefix('teknisi')->name('teknisi.')->group(function () {
            Route::get('/', Teknisi::class)->name('index');
            Route::get('create', Create::class)->name('create');
            Route::get('edit/{id}', Edit::class)->name('edit');
        });

        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', Customers::class)->name('index');
        });

        Route::prefix('log_activity')->name('log_activity.')->group(function () {
            Route::get('/', TabOnLogActivity::class)->name('index');
        });

        // Verification routes (master_admin only)
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/', Verification::class)->name('index');
        });

        // User Management routes (master_admin only)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UserManagement::class)->name('index');
        });

        // Payment Methods routes
        Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
            Route::get('/', PaymentMethods::class)->name('index');
        });
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});

//BACKEND AREA
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
