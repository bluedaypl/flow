<?php

use App\Models\Order;
use App\Models\Status;
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

    if(!$request->user()->isAdmin() && $order->assignUser()->exists() && $order->assignUser->id !== auth()->id()){
        return response()->json(['error' => 'Zamówienie jest przydzielone do '.$order->assignUser->name.', nie możesz zmienić status.'], 200);
    }

    $next = $order->nextStatus();
    if($next) {
        $order->setStatus($order->nextStatus());
    } else {
        $order->latestStatus()->makeDone();
    }

    if($order->suspended){
        $order->suspended = false;
        $order->save();
    }

    return $order->latestStatus();
});

Route::get('status/{order:id}', function (Request $request, Order $order) {
    $order_status = $order->latestStatus();
    return [
        'order' => $order->latestStatus(),
        'next' => $order->latestStatus() ? $order->latestStatus()->status->next() : Status::orderByFirst()->first(),
    ];
});
