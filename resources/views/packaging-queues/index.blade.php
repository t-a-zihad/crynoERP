@extends('parts.main')

@section('pageHeader', 'Packaging Queue')
@section('main-section')
<div class="table-responsive">
    <table class="table table-bordered table-hover  stripe">
        <thead class="thead-light">
            <tr>
                <th>Order ID</th>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Qty</th>
                <th>Priority</th>
                <th>Special Note</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packagingQueues as $queue)
                @php
                    $books = $orderedBooksGrouped[$queue->order_id] ?? collect();
                    $rowCount = $books->count();
                    $orderPriority = $books->first() && $books->first()->order ? $books->first()->order->order_priority : 'N/A';
                    $orderNote = $books->first() && $books->first()->order ? $books->first()->order->order_note : 'N/A';
                    // Define priority colors
                    $priorityColors = [
                        'High' => 'priority-high',     // Red text on a whitish background
                        'Normal' => 'priority-normal', // Yellow text on a whitish background
                        'Low' => 'priority-low'        // Navy blue text on a whitish background
                    ];

                    // Define status colors
                    $statusColors = [
                        'In queue' => 'status-queue',  // Purple
                        'Done' => 'status-success',    // Green
                        'Rejected' => 'status-rejected' // Red
                    ];

                    // Determine priority class
                    $priorityClass = $priorityColors[$orderPriority] ?? 'text-muted'; // Default 'N/A'

                    // Determine status class
                    $statusClass = $statusColors[$queue->status] ?? 'status-queue'; // Default to 'In Queue' color
                @endphp

                @foreach($books as $index => $book)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $rowCount }}">{{ $queue->order_id }}</td>
                        @endif
                        <td>{{ $book->ordered_book_id }}</td>
                        <td>{{ $book->book_name }}</td>
                        <td>{{ $book->qty }}</td>

                        <!-- Priority Column with color class -->
                        @if($index === 0)
                            <td rowspan="{{ $rowCount }}">
                                <span class="btn btn-sm {{ $priorityClass }}">
                                    {{ $orderPriority }}
                                </span>
                            </td>
                        @endif

                         @if($index === 0)
                            <td rowspan="{{ $rowCount }}">{{ $orderNote }}</td>
                         @endif

                        @if($index === 0)
                            <!-- Status Column with color class and form for updating status -->
                            <td rowspan="{{ $rowCount }}">
                                <form action="{{ route('packaging-queues.update', $queue->id) }}" method="POST" class="form-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control {{ $statusClass }}" onchange="this.form.submit()">
                                        @foreach(['In queue', 'Done', 'Rejected'] as $statusOption)
                                            <option value="{{ $statusOption }}"
                                                    {{ $queue->status === $statusOption ? 'selected' : '' }}>
                                                {{ $statusOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
