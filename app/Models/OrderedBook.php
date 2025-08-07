<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderedBook extends Model
{
    protected $fillable = [
        'order_id',
        'ordered_book_id',
        'book_name',
        'book_author',
        'binding_type',
        'lamination_type',
        'special_note',
        'book_pdf',
        'custom_cover',
        'cover',
        'unit_price',
        'qty',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function designQueue()
    {
        return $this->hasOne(DesignQueue::class, 'ordered_book_id', 'ordered_book_id');
    }

    public function printingQueue()
    {
        return $this->hasOne(PrintingQueue::class, 'ordered_book_id', 'ordered_book_id' );
    }

    public function coverPrintingQueue()
    {
        return $this->hasOne(CoverPrintingQueue::class, 'ordered_book_id', 'ordered_book_id');
    }

    public function bindingQueue()
    {
        return $this->hasOne(BindingQueue::class, 'ordered_book_id', 'ordered_book_id');
    }

    public function qcQueue()
    {
        return $this->hasOne(QcQueue::class, 'ordered_book_id', 'ordered_book_id');
    }

    public function packagingQueue()
    {
        return $this->hasOne(PackagingQueue::class, 'ordered_book_id', 'ordered_book_id');
    }

    public function shipmentQueue()
    {
        return $this->hasOne(ShipmentQueue::class, 'ordered_book_id', 'ordered_book_id');
    }
}
