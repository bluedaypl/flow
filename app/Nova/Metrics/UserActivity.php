<?php

namespace App\Nova\Metrics;

use App\Models\OrderStatus;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Metrics\MetricTableRow;
use Laravel\Nova\Metrics\Table;

class UserActivity extends Table
{
    public function name()
    {
        return __('Last User Activity');
    }


    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return OrderStatus::orderBy('updated_at', 'desc')->with(['user', 'order', 'order.producer'])->limit(5)->get()->map(function ($orderStatus) {
            return MetricTableRow::make()
                ->icon('user')
                ->iconClass('text-green-500')
                ->title(sprintf('%s zmienił status zamówienia %s (Producent: %s) na %s', $orderStatus->user->name, $orderStatus->order->shipment_number, $orderStatus->order->producer->name, $orderStatus->status->name))
                ->subtitle($orderStatus->updated_at->diffForHumans())
                ->actions(function () use ($orderStatus) {
                    return [
                        MenuItem::link('Zobacz zamówienie', '/resources/orders/'.$orderStatus->order->id)
                        // MenuItem::externalLink('Przejdź do zamówienia', $orderStatus->order->route()),
                    ];
                });
        });

        // return [
        //     MetricTableRow::make()
        //         ->icon('check-circle')
        //         ->iconClass('text-green-500')
        //         ->title('Silver Surfer')
        //         ->subtitle('In every part of the globe it is the same!'),
        // ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes();
    }
}
