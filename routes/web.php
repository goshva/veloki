<?php
use App\Http\Controllers\WebhookController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RentalCalculatorController;

Route::post('/api/calculate-rental', [RentalCalculatorController::class, 'calculate']);
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('finances', FinanceController::class);
Route::resource('orders', OrderController::class);
Route::post('/orders/{order}/finish', [OrderController::class, 'finish'])->name('orders.finish');

Route::get('/search-clients', [OrderController::class, 'searchClients'])->name('orders.search-clients');
Route::resource('prices', PriceController::class);
Route::post('/calculate-rental', [PriceController::class, 'calculateRental'])->name('prices.calculate-rental');
Route::resource('clients', ClientController::class);
Route::resource('bikes', BikeController::class);
Route::post('/webhook', [WebhookController::class, 'handle']);

Route::post('/api/calculate-rental', function (Request $request) {
    $calculator = new \App\Services\BikeRentalCalculator();
    
    $price = $calculator->calculatePrice(
        $request->bike_group,
        Carbon::parse($request->start_time),
        Carbon::parse($request->end_time)
    );
    
    return response()->json(['price' => $price]);
});