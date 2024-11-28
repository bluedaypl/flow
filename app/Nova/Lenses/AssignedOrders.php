<?php

namespace App\Nova\Lenses;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use App\Nova\Producer;
use App\Nova\Status;

class AssignedOrders extends Lens
{
    public function name()
    {
        return __('Your Orders');
    }

    public static function query(LensRequest $request, $query)
    {
        return $query;

        // return $query->where('assign_user_id', $request->user()->id);
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id'),
            Text::make(__('Shipment Number'), 'shipment_number'),
            BelongsTo::make(__('Producer'), 'producer', Producer::class),
            Badge::make(__('Priority'), 'priority')
                ->map([
                    0 => 'info',
                    1 => 'success',
                    2 => 'danger',
                ])
                ->label(function ($value) {
                    return [
                        0 => __('Low'),
                        1 => __('Normal'),
                        2 => __('High'),
                    ][$value];
                }),
            BelongsTo::make(__('Status'), 'status', Status::class),
            DateTime::make(__('Created At'), 'created_at'),
        ];
    }
}
