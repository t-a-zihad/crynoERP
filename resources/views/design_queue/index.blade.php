@extends('parts.main')

@section('pageHeader', 'Design Queue')
@section('main-section')
<table class="table table-bordered table-hover">
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
            <td>{{ $designQueue->orderedBook->order->order_priority ?? 'N/A' }}</td>
            <td>{{ $designQueue->orderedBook->special_note ?? '-' }}</td>
            <td>
                <form action="{{ route('design-queues.update', $designQueue->id) }}" method="POST" class="form-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{$designQueue->order_id}}">
                    <input type="hidden" name="ordered_book_id" value="{{$designQueue->ordered_book_id}}">

                    <select name="status" class="form-control" required onchange="this.form.submit()">
                        @php
                            $statuses = ['In Queue', 'Pre-designed', 'In Progress', 'Done', 'Rejected'];
                        @endphp
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $designQueue->status === $status ? 'selected' : '' }}>
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

