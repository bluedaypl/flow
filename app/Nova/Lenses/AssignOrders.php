<?php

namespace App\Nova\Lenses;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use App\Nova\Producer;
use App\Nova\Status;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
class AssignOrders extends Lens
{

    /**
     * Get the displayable name of the lens.
     *
     * @return string
     */
    public function name() {
        return __('Your Orders');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        $query->where('assign_user_id', $request->user()->id);

        if($request->query('orderBy')) {
            return $request->withOrdering($request->withFilters($query));
        }

        return $request->withOrdering($request->withFilters(
            $query->orderByRaw(' CASE WHEN done_at IS NULL THEN 0 ELSE 1 END,priority DESC,done_at ASC')
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id'),
            Text::make(__('Shipment Number'), 'shipment_number') ->sortable(),
            BelongsTo::make(__('Producer'), 'producer', Producer::class) ->sortable(),
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
                }) ->sortable(),
            BelongsTo::make(__('Status'), 'status', Status::class) ->sortable(),
            DateTime::make(__('Done At'), 'done_at')
            ->sortable()
            ->nullable()
            ->hideWhenCreating()
            ->hideWhenUpdating(),
            DateTime::make(__('Created At'), 'created_at') ->sortable(),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'assign-orders';
    }
}
