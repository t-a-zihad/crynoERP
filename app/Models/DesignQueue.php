<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignQueue extends Model
{
    protected $table = 'design_queues';

    protected $fillable = [
        'order_id',
        'ordered_book_id',
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

