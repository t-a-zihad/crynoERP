<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderedBook;
use App\Models\Order;

class PackagingQueue extends Model
{
    protected $table = 'packaging_queues';

    protected $fillable = [
        'order_id',
        'status',
        'handled_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function orderedBook()
    {
        return $this->belongsTo(OrderedBook::class, 'ordered_book_id', 'ordered_book_id');
    }






    public function handler()
    {
        return $this->belongsTo(Employee::class, 'handled_by');
    }
}
