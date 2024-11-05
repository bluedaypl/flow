<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Builder;

class Status extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $fillable = [
        'name',
    ];


    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];


    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    /**
     * The orders that belong to the Status
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_statuses');
    }

    /**
     * Get the next status
     *
     * @return \App\Models\Status | null
     */
    public function next()
    {
        return self::where('order', '>', $this->order)->orderBy('order', 'asc')->first();
    }

    /**
     * Get the previous status
     *
     * @return \App\Models\Status | null
     */
    public function previous()
    {
        return self::where('order', '<', $this->order)->orderBy('order', 'desc')->first();
    }

}
