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
                <form action="{{ route('orders.destroy', $order->order_id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                </form>
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



                        $statusColors = [
                            'Shipped' => 'status-success',
                            'In Queue' => 'status-queue',
                            'Rejected' => 'status-rejected',
                            'In Progress' => 'status-progress',
                        ];

                        $priorityColors = [
                            'High' => 'priority-high',
                            'Normal' => 'priority-normal',
                            'Low' => 'priority-low'
                        ];

                        $statusClass = $statusColors[$status] ?? 'status-queue';
                        $priorityClass = $priorityColors[$order->order_priority] ?? 'priority-low';
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
                <td class="highlighted">Courier Status & Tracking</td>
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
            </tr>
            <tr>
                <td class="highlighted">Status</td>
                <td><span class="btn btn-sm {{ $statusClass }}">{{ $status }}</td>
            </tr>
            <tr>
                <td class="highlighted">Priority</td>
                <td><span class="btn btn-sm {{ $priorityClass }}">{{ $order->order_priority ?? 'N/A' }}</span></td>
            </tr>
            <tr>
                <td class="highlighted">Customer Name</td>
                <td>{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <td class="highlighted">Mobile No</td>
                <td>{{ $order->phone_number }}</td>
            </tr>
            <tr>
                <td class="highlighted">Shipping Address</td>
                <td>{{ $order->shipping_address }}</td>
            </tr>
            <tr>
                <td class="highlighted">Book Count</td>
                <td>{{ $bookCount }}</td>
            </tr>
            <tr>
                <td class="highlighted">Total Price</td>
                <td>{{ $booksTotalPrice }}</td>
            </tr>
            <tr>
                <td class="highlighted">Delivery Charge</td>
                <td>{{ $deliveryCharge }}</td>
            </tr>
            <tr>
                <td class="highlighted">Discount</td>
                <td>{{ $discount }}</td>
            </tr>
            <tr>
                <td class="highlighted">Grand Total Price (BDT)</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
            <tr>
                <td class="highlighted">Manager</td>
                <td>{{ $order->handledBy->name ?? 'N/A' }}</td>
            </tr>



    </tbody>
</table>
</div>

<h5 class="mt-5 mb-1">Ordered Book Information</h5>

<div class="table-responsive">
    <table class="table table-bordered table-hover stripe">
        <thead class="thead-light">
            <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>Book ID</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Book Name</th>
                <th>Book Author</th>
                <th>Binding Type</th>
                <th>Lamination Type</th>
                <th>Special Note</th>
                <th>Book PDF</th>
                <th>Custom Cover</th>
                <th>Cover</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Design Status</th>
                <th>Printing Status</th>
                <th>Cover Printing Status</th>
                <th>Binding Status</th>
                <th>QC Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderedBooks as $book)
                @php
                    // Determine the status of the book based on queue statuses
                    $statuses = [
                        // Check design queue status and treat "Pre-designed" as "Done"
                        ($book->designQueue && $book->designQueue->status === 'Pre-designed') ? 'Done' : optional($book->designQueue)->status ?? 'In Queue',
                        optional($book->printingQueue)->status ?? 'In Queue',
                        optional($book->coverPrintingQueue)->status ?? 'In Queue',
                        optional($book->bindingQueue)->status ?? 'In Queue',
                        optional($book->qcQueue)->status ?? 'In Queue',
                    ];

                    // Normalize statuses to lowercase for comparison
                    $lowerStatuses = array_map('strtolower', $statuses);

                    if (count(array_unique($lowerStatuses)) === 1 && $lowerStatuses[0] === 'done') {
                        $overallStatus = 'Done';
                    } elseif (count(array_unique($lowerStatuses)) === 1 && $lowerStatuses[0] === 'in queue') {
                        $overallStatus = 'In Queue';
                    } else {
                        $overallStatus = 'In Progress';
                    }

                    $price = $book->unit_price * $book->qty;

                    // Define status color classes
                    $statusColors = [
                        'Done' => 'status-success',  // Green
                        'In Queue' => 'status-queue', // Purple
                        'Rejected' => 'status-rejected', // Red
                        'In Progress' => 'status-progress' // Blue
                    ];

                    // Define priority color classes
                    $priorityColors = [
                        'High' => 'priority-high',     // Red text on a whitish background
                        'Normal' => 'priority-normal', // Yellow text on a whitish background
                        'Low' => 'priority-low'        // Navy blue text on a whitish background
                    ];

                    // Determine the status class for the status column
                    $statusClass = $statusColors[$overallStatus] ?? 'status-progress';

                    // Determine the priority class for the priority column
                    $priorityClass = $priorityColors[$book->order->order_priority ?? 'Low'] ?? 'priority-low';

                    // Status classes for individual queue statuses
                    $statusClasses = [
                        'In Queue' => 'status-queue',
                        'Done' => 'status-success',
                        'Rejected' => 'status-rejected',
                        'In Progress' => 'status-progress'
                    ];

                    $designQueueClass = $statusClasses[$book->designQueue->status ?? 'In Queue'] ?? 'status-queue';
                    $printingQueueClass = $statusClasses[$book->printingQueue->status ?? 'In Queue'] ?? 'status-queue';
                    $coverPrintingQueueClass = $statusClasses[$book->coverPrintingQueue->status ?? 'In Queue'] ?? 'status-queue';
                    $bindingQueueClass = $statusClasses[$book->bindingQueue->status ?? 'In Queue'] ?? 'status-queue';
                    $qcQueueClass = $statusClasses[$book->qcQueue->status ?? 'In Queue'] ?? 'status-queue';

                @endphp
                <tr>
                    <td>{{ $book->created_at->format('Y-m-d') }}</td>
                    <td>{{ $book->order_id }}</td>
                    <td>{{ $book->ordered_book_id }}</td>

                    <!-- Priority Column with color class -->
                    <td>
                        <span class="btn btn-sm {{ $priorityClass }}">
                            {{ $book->order->order_priority ?? 'N/A' }}
                        </span>
                    </td>

                    <!-- Status Column with color class -->
                    <td>
                        <span class="btn btn-sm {{ $statusClass }}">
                            {{ $overallStatus }}
                        </span>
                    </td>

                    <td>{{ $book->book_name }}</td>
                    <td>{{ $book->book_author ?? '-' }}</td>
                    <td>{{ $book->binding_type }}</td>
                    <td>{{ $book->lamination_type }}</td>
                    <td>{{ $book->special_note ?? '-' }}</td>
                    <td>
                        @if($book->pdf_link)
                            <a href="{{ $book->pdf_link }}" target="_blank">PDF</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $book->custom_cover ? 'Yes' : 'No' }}</td>
                    <td>
                        @if($book->cover_link)
                            <a href="{{ $book->cover_link }}" target="_blank">Cover</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ number_format($book->unit_price, 2) }}</td>
                    <td>{{ $book->qty }}</td>
                    <td>{{ number_format($price, 2) }}</td>

                    <!-- Design Queue Status -->
                    <td>
                        <span class="btn btn-sm {{ $designQueueClass }}">
                            {{ $book->designQueue->status ?? 'In Queue' }}
                        </span>
                    </td>

                    <!-- Printing Queue Status -->
                    <td>
                        <span class="btn btn-sm {{ $printingQueueClass }}">
                            {{ $book->printingQueue->status ?? 'In Queue' }}
                        </span>
                    </td>

                    <!-- Cover Printing Queue Status -->
                    <td>
                        <span class="btn btn-sm {{ $coverPrintingQueueClass }}">
                            {{ $book->coverPrintingQueue->status ?? 'In Queue' }}
                        </span>
                    </td>

                    <!-- Binding Queue Status -->
                    <td>
                        <span class="btn btn-sm {{ $bindingQueueClass }}">
                            {{ $book->bindingQueue->status ?? 'In Queue' }}
                        </span>
                    </td>

                    <!-- QC Queue Status -->
                    <td>
                        <span class="btn btn-sm {{ $qcQueueClass }}">
                            {{ $book->qcQueue->status ?? 'In Queue' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<hr>
@if($order->shipmentQueue)
    <h5 class="mt-5 mb-1">Ordered Book Information</h5>
    @if ($order->shipmentQueue->tracking_code)
        <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="https://steadfast.com.bd/t/{{$order->shipmentQueue->tracking_code}}" allowfullscreen></iframe>
        </div>
    @endif
@endif

@endsection
