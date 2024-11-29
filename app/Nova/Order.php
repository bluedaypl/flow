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
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Panel;
use App\Nova\Lenses\AssignedOrders;
use App\Nova\Lenses\AssignOrders;
use App\Nova\Metrics\AssignOrder;
use App\Nova\Metrics\AssignOrderCount;
use Blueday\Assignorders\Assignorders as AssignordersAssignorders;
use Laravel\Nova\Fields\Boolean;

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

    // public static $clickAction = 'edit';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'shipment_number';

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderByRaw('CASE WHEN done_at IS NULL THEN 0 ELSE 1 END,priority DESC,done_at ASC');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'shipment_number',
        'producer.name'
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
                })
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make(__('Shipment Number'), 'shipment_number')
                ->sortable()
                ->required()
                ->default(function ($request) {
                    return $request->cookie('order_shipment_number', '');
                })
                ->creationRules('unique:orders,shipment_number')
                ->updateRules('unique:orders,shipment_number,{{resourceId}}'),


            BelongsTo::make(__('Producer'), 'producer', Producer::class)
                ->sortable()
                ->searchable()
                ->required(),


            $request->user()->isAdmin()
            ? BelongsTo::make(__('Assign User'), 'assignUser', User::class)->sortable()->nullable()
            : BelongsTo::make(__('Assign User'), 'assignUser', User::class)->sortable()->hideWhenCreating()->hideWhenUpdating(),

            Select::make(__('Priority'), 'priority')
                ->options([
                    0 => __('Low'),
                    1 => __('Normal'),
                    2 => __('High'),
                ])
                ->default(1)
                ->displayUsingLabels()
                ->sortable()
                ->required()
                ->onlyOnForms(),

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

            Boolean::make(__('Suspended'), 'suspended')
                ->sortable()
                ->hideFromIndex()
                ->hideFromDetail()
                ->hideWhenUpdating()
                ->help('Zamówienie jest tworzone, ale nie jest rozpoczonany proces realizacji.')->canSee(function ($request) {
                    return $request->user()->isAdmin();
                }),

            HasMany::make(__('Statuses'), 'statuses', OrderStatus::class),

            // Text::make('Status')->resolveUsing(function () {
            //     return $this->latestStatus()->status->name ?? '-';
            // })->onlyOnIndex(),


            // BelongsTo::make(__('Statuses'), 'statuses', Status::class)
        ];
    }


    public function assignFields(NovaRequest $request)
    {
        return [
            BelongsTo::make(__('Assign User'), 'assignUser', User::class)->sortable()->nullable()
                ->canSee(function ($request) {
                    return $request->user()->isAdmin();
                }),
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
        return [
            new AssignordersAssignorders
            // AssignOrder::make()->width('full')
            // AssignOrderCount::make()->width('1/2'),
        ];
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
        return [
            new AssignOrders,
        ];
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
