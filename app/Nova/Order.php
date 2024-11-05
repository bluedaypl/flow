<?php

namespace App\Nova;

use App\Nova\Actions\PreviousOrderStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Blueday\OrderStatus\OrderStatus as OrderStatusTool;
use App\Nova\Filters\OrderStatus as OrderStatusFilter;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Panel;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order>
     */
    public static $model = \App\Models\Order::class;


    public static function label()
    {
        return __('Orders');
    }

    public static function singularLabel()
    {
        return __('Order');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'shipment_number'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Shipment Number'), 'shipment_number')
                ->sortable()
                ->required()
                ->default(function ($request) {
                    return $request->cookie('order_shipment_number', '');
                })
                ->creationRules('unique:orders,shipment_number')
                ->updateRules('unique:orders,shipment_number'),
            BelongsTo::make(__('Producer'), 'producer', Producer::class)
                ->sortable()
                ->required(),

            BelongsTo::make(__('Status'), 'status', Status::class)
            ->sortable()
            ->required()->default(function ($request) {
                return \App\Models\Status::first()->id;
            })->hideWhenUpdating()->hideWhenCreating(),

            DateTime::make(__('Created At'), 'created_at')
                ->sortable()
                ->nullable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            DateTime::make(__('Done At'), 'done_at')
                ->sortable()
                ->nullable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            OrderStatusTool::make(),

            HasMany::make(__('Statuses'), 'statuses', OrderStatus::class),


            // Text::make('Status')->resolveUsing(function () {
            //     return $this->latestStatus()->status->name ?? '-';
            // })->onlyOnIndex(),


            // BelongsTo::make(__('Statuses'), 'statuses', Status::class)
        ];
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new OrderStatusFilter
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            PreviousOrderStatus::make()
        ];
    }
}
