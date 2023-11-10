<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentsController;
use App\Jobs\CreateRoast;
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

Route::get('/test', function (): void {
    $payment = Payment::query()->create([
        'name' => 'Test'.time(),
        'email' => 'test'.time().'@test.com',
        'token' => 'tok_visa',
        'stripe_id' => 'cus_test',
        'url' => 'https://namealerts.io',
    ]);

    CreateRoast::dispatch($payment);
});

Route::get('/', HomeController::class);

Route::post('/payments', (new PaymentsController())->store(...))->name('payments.store');
Route::get('/payments', (new PaymentsController())->index(...))->name('payments.index');
