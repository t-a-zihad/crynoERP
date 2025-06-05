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
            @endphp

            @foreach($books as $index => $book)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}">{{ $queue->order_id }}</td>
                    @endif
                    <td>{{ $book->ordered_book_id }}</td>
                    <td>{{ $book->book_name }}</td>
                    <td>{{ $book->qty }}</td>
                    <td>{{ $orderPriority }}</td>
                    <td>{{ $book->special_note ?? '-' }}</td>

                    @if($index === 0)
                        <td rowspan="{{ $rowCount }}">
                            <form action="{{ route('packaging-queues.update', $queue->id) }}" method="POST" class="form-inline">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    @php
                                        $statuses = ['In queue', 'Done', 'Rejected'];
                                    @endphp
                                    @foreach($statuses as $statusOption)
                                        <option value="{{ $statusOption }}" {{ $queue->status === $statusOption ? 'selected' : '' }}>
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
