<?php

namespace App\Observers;

use App\Models\OrderStatus;
use Carbon\Carbon;

class OrderStatusObserver
{

    public function creating(OrderStatus $orderStatus): void
    {
       if($orderStatus->user_id === null) {
           $orderStatus->user_id = auth()->id();
       }
       $orderStatus->started_at = now();
    }

    /**
     * Handle the OrderStatus "created" event.
     */
    public function created(OrderStatus $orderStatus): void
    {
        $orderStatus->order->update([
            'status_id' => $orderStatus->status_id,
            'done_at' => null,
        ]);

    }

    public function updating(OrderStatus $orderStatus): void
    {
        if ($orderStatus->isDirty('ended_at')) {
            $orderStatus->in_status = Carbon::parse($orderStatus->ended_at)->diffInSeconds(Carbon::parse($orderStatus->started_at));
        }
    }



    /**
     * Handle the OrderStatus "updated" event.
     */
    public function updated(OrderStatus $orderStatus): void
    {
    }

    /**
     * Handle the OrderStatus "deleted" event.
     */
    public function deleted(OrderStatus $orderStatus): void
    {
        //
    }

    /**
     * Handle the OrderStatus "restored" event.
     */
    public function restored(OrderStatus $orderStatus): void
    {
        //
    }

    /**
     * Handle the OrderStatus "force deleted" event.
     */
    public function forceDeleted(OrderStatus $orderStatus): void
    {
        //
    }
}
