<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentsController;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/test', function () {
    Payment::query()->create([
        'name' => 'Test',
        'email' => 'test@test.com',
        'stripe_id' => 'test',
        'url' => 'https://fdbck.io',
    ]);
});

Route::get('/', HomeController::class);
Route::post('/payments', (new PaymentsController())->store(...))->name('payments.store');
Route::get('/roasts/{payment:uuid}', (new PaymentsController())->show(...))->name('payments.show');
