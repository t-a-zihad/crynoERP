@extends('parts.main')

@section('pageHeader', 'QC Queue')

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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $queue)
                @php
                    // Define status colors
                    $statusColors = [
                        'Done' => 'status-success',    // Green
                        'In Queue' => 'status-queue',  // Purple
                        'Rejected' => 'status-rejected' // Red
                    ];

                    // Define priority colors
                    $priorityColors = [
                        'High' => 'priority-high',     // Red text on a whitish background
                        'Normal' => 'priority-normal', // Yellow text on a whitish background
                        'Low' => 'priority-low'        // Navy blue text on a whitish background
                    ];

                    // Determine the current status color class
                    $statusClass = $statusColors[$queue->status] ?? 'status-queue'; // Default to purple (In Queue)

                    // Determine the current priority color class
                    $priorityClass = $priorityColors[$queue->orderedBook->order->order_priority] ?? 'priority-low'; // Default to navy blue (Low)
                @endphp
                <tr>
                    <td>{{ $queue->ordered_book_id }}</td>
                    <td>{{ $queue->orderedBook->book_name ?? 'N/A' }}</td>
                    <td>{{ $queue->orderedBook->qty ?? 'N/A' }}</td>

                    <!-- Priority Column with color class -->
                    <td>
                        <span class="btn btn-sm {{ $priorityClass }}">
                            {{ $queue->orderedBook->order->order_priority ?? 'N/A' }}
                        </span>
                    </td>

                    <td>{{ $queue->orderedBook->special_note ?? '-' }}</td>
                    <td>
                        <form action="{{ route('qc-queues.update', $queue->id) }}" method="POST" class="form-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control {{ $statusClass }}" required onchange="this.form.submit()">
                                @foreach(['In Queue', 'Done', 'Rejected'] as $status)
                                    <option value="{{ $status }}"
                                            {{ $queue->status === $status ? 'selected' : '' }}>
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
