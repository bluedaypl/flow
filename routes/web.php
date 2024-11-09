<?php

use App\Http\Controllers\NovaResourceRedirectController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware(['nova'])->get('/order/scanner', 'App\Http\Controllers\OrderController@scanner')->name('order.scanner');

Route::get('/nova-redirect/resources/{resource}/{id?}', NovaResourceRedirectController::class)->name('nova.resource');