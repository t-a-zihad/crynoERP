<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentQueue extends Model
{
    protected $table = 'shipment_queues';

    protected $fillable = [
        'order_id',
        'status',
        'handled_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function handler()
    {
        return $this->belongsTo(Employee::class, 'handled_by');
    }
}
