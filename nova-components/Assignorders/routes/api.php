<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

Route::get('/assigned-orders-count', function (Request $request) {
    $count = Order::where('assign_user_id', auth()->id())->whereNull('done_at')->count();
    return response()->json(['count' => $count]);
});
