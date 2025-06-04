@extends('parts.main')

@section('pageHeader', 'Cover Printing Queue')

@section('main-section')
<table class="table table-bordered table-hover">
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
        <tr>
            <td>{{ $queue->ordered_book_id }}</td>
            <td>{{ $queue->orderedBook->book_name ?? 'N/A' }}</td>
            <td>{{ $queue->orderedBook->qty ?? 'N/A' }}</td>
            <td>{{ $queue->orderedBook->order->order_priority ?? 'N/A' }}</td>
            <td>{{ $queue->orderedBook->special_note ?? '-' }}</td>
            <td>
                @if(!empty($queue->orderedBook->cover))
                    <a href="{{ $queue->orderedBook->cover }}" target="_blank">View Cover</a>
                @else
                    -
                @endif
            </td>
            <td>{{ $queue->printing_status }}</td>
            <td>
                <form action="{{ route('cover-printing-queues.update', $queue->id) }}" method="POST" class="form-inline">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-control" required onchange="this.form.submit()">
                        @php
                            $statuses = ['In Queue', 'Rendered', 'Done', 'Rejected'];
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
@endsection
