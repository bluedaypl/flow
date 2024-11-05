<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function scanner(Request $request)
    {
        if(!$request->has('shipment_number')) 
            return redirect('/resources/orders');

        $order = Order::where('shipment_number', $request->input('shipment_number'))->exists();
        if ($order) {
            return redirect('/resources/orders/new');
        }
        else {
            return redirect('/resources/orders/new')->withCookie(cookie('order_shipment_number', $request->input('shipment_number'), 1));
        }
    }
    //
}
