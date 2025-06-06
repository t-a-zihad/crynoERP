@extends('parts.main')

@section('pageHeader', 'Design Queue')
@section('main-section')
<div class="table-responsive">
<table class="table table-bordered table-hover  stripe">
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
        @foreach($designQueues as $designQueue)
        <tr>
            <td>{{ $designQueue->ordered_book_id }}</td>
            <td>{{ $designQueue->orderedBook->book_name ?? 'N/A' }}</td>
            <td>{{ $designQueue->orderedBook->qty ?? 'N/A' }}</td>

            @php
                $priorityColors = [
                    'High' => 'priority-high',     // Red text on a whitish background
                    'Normal' => 'priority-normal', // Yellow text on a whitish background
                    'Low' => 'priority-low'        // Navy blue text on a whitish background
                ];

                // Determine the class based on the priority value
                $priorityClass = $priorityColors[$designQueue->orderedBook->order->order_priority] ?? 'text-muted'; // Default 'N/A'
            @endphp

            <td>
                <span class="btn btn-sm {{ $priorityClass }}">
                    {{ $designQueue->orderedBook->order->order_priority ?? 'N/A' }}
                </span>
            </td>

            <td>{{ $designQueue->orderedBook->special_note ?? '-' }}</td>
            <td>
                <form action="{{ route('design-queues.update', $designQueue->id) }}" method="POST" class="form-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{$designQueue->order_id}}">
                    <input type="hidden" name="ordered_book_id" value="{{$designQueue->ordered_book_id}}">

                    @php
                        $statuses = ['In Queue', 'Pre-designed', 'In Progress', 'Done', 'Rejected'];
                        $statusColors = [
                            'In Queue' => 'status-queue',         // Purple
                            'Pre-designed' => 'status-progress',  // Blue
                            'In Progress' => 'status-progress',   // Blue
                            'Done' => 'status-success',           // Green
                            'Rejected' => 'status-rejected'       // Red
                        ];

                        // Determine the current status color class
                        $statusClass = $statusColors[$designQueue->status] ?? 'status-queue';
                    @endphp

                    <select name="status" class="form-control {{ $statusClass }}" required onchange="this.form.submit()">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}"
                                    {{ $designQueue->status === $status ? 'selected' : '' }}>
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

