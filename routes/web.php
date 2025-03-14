<?php
use App\Http\Controllers\WebhookController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook', [WebhookController::class, 'handle']);