@extends('parts.main')

@section('pageHeader', 'Binding Queue')

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
        @foreach($queues as $queue)
        <tr>
            <td>{{ $queue->ordered_book_id }}</td>
            <td>{{ $queue->orderedBook->book_name ?? 'N/A' }}</td>
            <td>{{ $queue->orderedBook->qty ?? 'N/A' }}</td>
            <td>{{ $queue->orderedBook->order->order_priority ?? 'N/A' }}</td>
            <td>{{ $queue->orderedBook->special_note ?? '-' }}</td>
            <td>
                <form action="{{ route('binding-queues.update', $queue->id) }}" method="POST" class="form-inline">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-control" required onchange="this.form.submit()">
                        @php
                            $statuses = ['In Queue', 'Sent', 'Returned', 'Done', 'Rejected'];
                        @endphp
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $queue->status === $status ? 'selected' : '' }}>
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
