<?php

namespace App\Nova\Filters;

use App\Models\Status;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class OrderStatus extends Filter
{
    public function name()
    {
        return __('Status');
    }

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        if($value == 'Zakończono') {
            return $query->whereNotNull('done_at');
        }
        return $query->where('status_id', Status::where('name', $value)->first()->id);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return 
        Status::all()->pluck('name', 'id')
        ->add('Zakończono', 'close')
        ->toArray();
    }
}
