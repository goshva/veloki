<?php
use App\Http\Controllers\WebhookController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\DashboardController;
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('finances', FinanceController::class);
Route::resource('orders', OrderController::class);
Route::get('/search-clients', [OrderController::class, 'searchClients'])->name('orders.search-clients');
Route::resource('prices', PriceController::class);
Route::resource('clients', ClientController::class);
Route::resource('bikes', BikeController::class);
Route::post('/webhook', [WebhookController::class, 'handle']);