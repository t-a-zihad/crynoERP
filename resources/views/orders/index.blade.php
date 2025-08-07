@extends('parts.main')

@section('pageHeader', 'All Orders')

@section('main-section')
<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th>Date</th>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Book Count</th>
            <th>Manager</th>
            <th>Grand Total Price (BDT)</th>
            <th>Status</th>
            <th>Courier Status & Tracking</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
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
                } elseif (strtolower($shipmentStatus) === 'shipped') {
                    $status = 'Shipped';
                }
            @endphp
            <tr>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $bookCount }}</td>
                <td>{{ $order->handledBy->name ?? 'N/A' }}</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
                <td>{{ $status }}</td>
                <td>
                    @if($order->shipmentQueue)
                        @if ($order->shipmentQueue->tracking_code)
                            <a href="https://steadfast.com.bd/t/{{$order->shipmentQueue->tracking_code}}" data-toggle="tooltip" title="{{$order->detailedStatus}}" class="btn btn-sm btn-secondary" target="_blank">{{$order->shortStatus}}</a>
                        @else
                            N/A
                        @endif
                    @else
                            N/A
                    @endif
                </td>
                <td>
                    <a href="{{ route('orders.edit', $order->order_id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('orders.show', $order->order_id) }}" class="btn btn-sm btn-primary">View</a>
                    <a href="{{ route('orders.invoice', $order->order_id) }}" class="btn btn-sm btn-success">Invoice</a>

                    <form action="{{ route('orders.destroy', $order->order_id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
