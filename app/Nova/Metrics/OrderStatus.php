<?php

namespace App\Nova\Metrics;

use App\Models\OrderStatus as ModelsOrderStatus;
use App\Models\Status;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class OrderStatus extends Partition
{

    public $width = '1/2';

    public function name()
    {
        return 'Średni czas trwania etapów zamówień (w minutach)';
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $list = ModelsOrderStatus::whereNotNull('ended_at')->groupBy('status_id')->select([
            'statuses.name',
            DB::raw('AVG(TIMESTAMPDIFF(SECOND, started_at, ended_at)) as average')
        ])
        ->leftJoin('statuses', 'order_statuses.status_id', '=', 'statuses.id')
        ->get();

        $data = $list->mapWithKeys(function ($item) {
            return [$item->name => round($item->average / 60)];
        });

        return $this->result($data->toArray());
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'order-status';
    }
}
