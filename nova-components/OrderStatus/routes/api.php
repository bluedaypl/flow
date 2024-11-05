<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::post('/next-status/{order:id}', function (Request $request, Order $order) {
    $order->latestStatus()->setNext();
    return $order->latestStatus();
});

Route::get('status/{order:id}', function (Request $request, Order $order) {
    return [
        'order' => $order->latestStatus(),
        'next' => $order->latestStatus()->status->next() ?? null,
    ];
});