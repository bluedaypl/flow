<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Status;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\Nova;
use Laravel\Nova\URL;

class OrderObserver
{

    public function creating(Order $order): void
    {
        $order->status_id = $order->suspended ? null : Status::first()->id;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        if(!$order->suspended)
        {
            OrderStatus::create([
                'order_id' => $order->id,
                'status_id' => $order->status_id,
                'started_at' => now(),
                'user_id' => auth()->id() ?? 1,
            ]);
        }

        // Notifiy
        if($order->assignUser) {
            $order->assignUser->notify(NovaNotification::make()
            ->message(sprintf('Zamówienie #%d (%s) zostało przydzielone do Ciebie',
                $order->id,
                $order->producer->name))
            ->action('Zobacz', Nova::url('/resources/orders/'.$order->id))
            ->icon('truck')
            ->type('info'));
        }

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
