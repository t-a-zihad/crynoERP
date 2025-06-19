@extends('parts.main')

@section('pageHeader', 'All Books')

@section('main-section')
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
            @foreach($books as $book)
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
@endsection
