<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentsController;
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

Route::get('/', HomeController::class)->name('home');
Route::post('/payments', (new PaymentsController())->store(...))->name('payments.store');
Route::get('/roasts/{payment:uuid}', (new PaymentsController())->show(...))->name('payments.show');
