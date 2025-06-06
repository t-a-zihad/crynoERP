@extends('parts.main')

@section('pageHeader', 'Cover Printing Queue')

@section('main-section')
<div class="table-responsive">
    <table class="table table-bordered table-hover stripe">
        <thead class="thead-light">
            <tr>
                <th>Ordered Book ID</th>
                <th>Book Name</th>
                <th>Qty</th>
                <th>Priority</th>
                <th>Special Note</th>
                <th>Cover</th>
                <th>Book Print Status</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $queue)
                @php
                    // Define priority colors based on the value
                    $priorityColors = [
                        'High' => 'priority-high',     // Red text on a whitish background
                        'Normal' => 'priority-normal', // Yellow text on a whitish background
                        'Low' => 'priority-low'        // Navy blue text on a whitish background
                    ];

                    // Determine the class based on the priority value
                    $priorityClass = $priorityColors[$queue->orderedBook->order->order_priority] ?? 'text-muted'; // Default 'N/A'

                    // Define status colors
                    $statusColors = [
                        'In Queue' => 'status-queue',   // Purple
                        'Rendered' => 'status-progress',// Blue
                        'Done' => 'status-success',     // Green
                        'Rejected' => 'status-rejected' // Red
                    ];

                    // Determine the current status color class
                    $statusClass = $statusColors[$queue->status] ?? 'status-queue';
                @endphp

                <tr>
                    <td>{{ $queue->ordered_book_id }}</td>
                    <td>{{ $queue->orderedBook->book_name ?? 'N/A' }}</td>
                    <td>{{ $queue->orderedBook->qty ?? 'N/A' }}</td>

                    <!-- Priority Column -->
                    <td>
                        <span class="btn btn-sm {{ $priorityClass }}">
                            {{ $queue->orderedBook->order->order_priority ?? 'N/A' }}
                        </span>
                    </td>

                    <td>{{ $queue->orderedBook->special_note ?? '-' }}</td>

                    <td>
                        @if(!empty($queue->orderedBook->cover))
                            <a href="{{ $queue->orderedBook->cover }}" target="_blank">View Cover</a>
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $queue->printing_status }}</td>

                    <!-- Status Column (Editable) -->
                    <td>
                        <form action="{{ route('cover-printing-queues.update', $queue->id) }}" method="POST" class="form-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control {{ $statusClass }}" required onchange="this.form.submit()">
                                @foreach(['In Queue', 'Rendered', 'Done', 'Rejected'] as $status)
                                    <option value="{{ $status }}" {{ $queue->status == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
