@extends('parts.main')

@section('pageHeader', 'Print Queue')

@section('main-section')
<div class="table-responsive">
    <table class="table table-bordered table-hover stripe">
        <thead class="thead-light">
            <tr>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Quantity</th>
                <th>Priority</th>
                <th>Special Note</th>
                <th>PDF Link</th>
                <th>Cover Print Status</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $book = $item->orderedBook;
                    $order = $book->order ?? null;
                    $priorityColors = [
                        'High' => 'priority-high',     // Red text on a whitish background
                        'Normal' => 'priority-normal', // Yellow text on a whitish background
                        'Low' => 'priority-low'        // Navy blue text on a whitish background
                    ];

                    // Determine the class based on the priority value
                    $priorityClass = $priorityColors[$order->order_priority] ?? 'text-muted'; // Default 'N/A'

                    $statusColors = [
                        'In Queue' => 'status-queue',         // Purple
                        'In Progress' => 'status-progress',   // Blue
                        'Done' => 'status-success',           // Green
                        'Rejected' => 'status-rejected'       // Red
                    ];

                    // Determine the current status color class
                    $statusClass = $statusColors[$item->status] ?? 'status-queue';
                @endphp
                <tr>
                    <td>{{ $item->ordered_book_id }}</td>
                    <td>{{ $book->book_name ?? 'N/A' }}</td>
                    <td>{{ $book->qty ?? 'N/A' }}</td>
                    <td>
                        <span class="btn btn-sm {{ $priorityClass }}">
                            {{ $order->order_priority ?? 'N/A' }}
                        </span>
                    </td>
                    <td>{{ $book->special_note ?? '-' }}</td>
                    <td>
                        @if($book->pdf_link)
                            <a href="{{ $book->pdf_link }}" target="_blank">View PDF</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->cover_printing_status }}</td>
                    <td>
                        <form action="{{ route('printing-queues.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control form-control-sm {{ $statusClass }}" onchange="this.form.submit()">
                                @foreach(['In Queue', 'In Progress', 'Done', 'Rejected'] as $status)
                                    <option value="{{ $status }}" {{ $item->status == $status ? 'selected' : '' }}>
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
