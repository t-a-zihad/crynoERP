<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PackagingQueue;
use App\Models\ShipmentQueue;
use App\Models\Employee;
use App\Models\OrderedBook;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'order_date',
        'order_priority',
        'customer_name',
        'phone_number',
        'shipping_address',
        'delivery_type',
        'delivery_charge',
        'discount',
        'order_note',
        'handled_by',
    ];

    // Relationship with OrderedBook
    public function orderedBooks()
    {
        return $this->hasMany(OrderedBook::class, 'order_id', 'order_id');
    }

    // Relationship with Employee as manager
    public function handledBy()
    {
        return $this->belongsTo(Employee::class, 'handled_by', 'id');
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


    // Relationships for packaging and shipment queue
    public function packagingQueue()
    {
        return $this->hasOne(PackagingQueue::class, 'order_id', 'order_id');
    }

    public function shipmentQueue()
    {
        return $this->hasOne(ShipmentQueue::class, 'order_id', 'order_id');
    }


}
