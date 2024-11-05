<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Status;

class OrderObserver
{

    public function creating(Order $order): void
    {
        $order->status_id = Status::first()->id;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        OrderStatus::create([
            'order_id' => $order->id,
            'status_id' => $order->status_id,
            'started_at' => now(),
            'user_id' => auth()->id() ?? 1,
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
