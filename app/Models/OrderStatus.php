<?php

namespace App\Models;

use App\Observers\OrderStatusObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\DB;

#[ObservedBy([OrderStatusObserver::class])]
class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status_id',
        'user_id',
        'started_at',
        'ended_at',
        'status_id'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'done_at' => 'datetime',
    ];


    /**
     * Get the order that owns the OrderStatus
     *
     * @return void
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status that owns the OrderStatus
     *
     * @return void
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function diff(): String
    {
        if ($this->ended_at === null) {
            return Carbon::parse($this->started_at)->diffForHumans(null, true);
        } else {
            return Carbon::parse($this->ended_at)->diffForHumans(Carbon::parse($this->started_at), true);
        }
    }

    /**
     * Make the order status done
     *
     * @return void
     */
    public function makeDone(): bool
    {
        if($this->ended_at == null) {
            $this->ended_at = now();
            $this->save();
        }
        if(!$this->status->next())
            $this->order->setDone();

        return true;
    }

    /**
     * Move the order status to the next status
     *
     * @return void
     */
    public function setNext() {
        $nextStatus = Status::where('order', '>', $this->status->order)->first();
        if(!$nextStatus) {
            $this->makeDone();
            return true;
        }
        return $this->order->setStatus($nextStatus);
    }

    public function setPrevious() {
        if($this->order->status->order == Status::max('order') && $this->ended_at !== null) {
            $this->order->setStatus($this->status);
        } else {
            $this->order->setStatus($this->status->previous());
        }
    }
}
