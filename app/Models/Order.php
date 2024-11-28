<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'shipment_number',
        'producer_id',
        'status_id',
        'assign_user_id',
        'suspended',
        'done_at'
    ];

    protected $casts = [
        'done_at' => 'datetime',
    ];

    /**
     * Get the producer that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }

    /**
     * The statuses that belong to the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function statuses()
    {
        return $this->hasMany(OrderStatus::class, 'order_id');
    }

    /**
     * Get the latest status of the order
     *
     * @return \App\Models\OrderStatus | null
     */
    public function latestStatus()
    {
        return $this->statuses()->latest()->with('status')->first();
    }

    public function assignUser()
    {
        return $this->belongsTo(User::class, 'assign_user_id');
    }

    /**
     * Get the status of the order
     *
     * @return void
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Set the status of the order
     *
     * @param Status $status
     * @return Status
     */
    public function setStatus(Status $status)
    {
        $latestStatus = $this->latestStatus();
        if ($latestStatus)
            $latestStatus->makeDone();

        $this->statuses()->create([
            'status_id' => $status->id,
        ]);

        return $status;
    }

    public function nextStatus()
    {
        $latestStatus = $this->latestStatus();
        return $latestStatus ? $latestStatus->status->next() : Status::orderByFirst()->first();
    }

    /**
     * Set the order as done
     *
     * @return void
     */
    public function setDone(){
        $this->done_at = now();
        $this->save();
    }

    public function route()
    {
        return route('nova.resource', ['resource' => 'orders', 'id' => $this->id]);
    }

}
