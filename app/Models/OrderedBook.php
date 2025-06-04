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
        return $this->hasOne(DesignQueue::class);
    }

    public function printingQueue()
    {
        return $this->hasOne(PrintingQueue::class);
    }

    public function coverPrintingQueue()
    {
        return $this->hasOne(CoverPrintingQueue::class);
    }

    public function bindingQueue()
    {
        return $this->hasOne(BindingQueue::class);
    }

    public function qcQueue()
    {
        return $this->hasOne(QcQueue::class);
    }

    public function packagingQueue()
    {
        return $this->hasOne(PackagingQueue::class);
    }

    public function shipmentQueue()
    {
        return $this->hasOne(ShipmentQueue::class);
    }
}
