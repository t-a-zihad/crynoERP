@extends('parts.main')

@section('pageHeader', 'View Order')

@section('main-section')
<style>
    .highlighted {
        background-color: #e9ecef;  /* Light grey background */
        font-weight: bold;          /* Make the text bold */
    }
    table tr td:nth-child(1){
        width: 80px
    }
</style>

<div class="table-responsive">


<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="highlighted">Actions</th>
            <th>
                <a href="{{ route('orders.edit', $order->order_id) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('orders.invoice', $order->order_id) }}" class="btn btn-sm btn-warning">Invoice</a>
            </th>
        </tr>
    </thead>
    <tbody>

            @php
                $bookCount = $order->orderedBooks->count();

                $booksTotalPrice = $order->orderedBooks->sum(function($book) {
                    return $book->unit_price * $book->qty;
                });

                $deliveryCharge = $order->delivery_charge ?? 0;
                $discount = $order->discount ?? 0;

                $grandTotal = $booksTotalPrice + $deliveryCharge - $discount;

                // Determine status based on packaging and shipment queues
                $packagingStatus = optional($order->packagingQueue)->status ?? 'In Queue';
                $shipmentStatus = optional($order->shipmentQueue)->status ?? 'In Queue';

                $status = 'In Progress';
                if (strtolower($packagingStatus) === 'in queue' && strtolower($shipmentStatus) === 'in queue') {
                    $status = 'In Queue';
                } elseif (strtolower($packagingStatus) === 'done' && strtolower($shipmentStatus) === 'done') {
                    $status = 'Shipped';
                }
            @endphp
            <tr>
                <td class="highlighted">Date</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td class="highlighted">Order ID</td>
                <td>{{ $order->order_id }}</td>
            </tr>
            <tr>
                <td class="highlighted">Customer Name</td>
                <td>{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <td class="highlighted">Book Count</td>
                <td>{{ $bookCount }}</td>
            </tr>
            <tr>
                <td class="highlighted">Manager</td>
                <td>{{ $order->handledBy->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="highlighted">Grand Total Price (BDT)</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
            <tr>
                <td class="highlighted">Status</td>
                <td>{{ $status }}</td>
            </tr>


    </tbody>
</table>


</div>
@endsection
