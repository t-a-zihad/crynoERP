@extends('parts.main')

@section('pageHeader', 'Shipment Queue')

@section('main-section')
<div class="table-responsive">
<table class="table table-bordered table-hover  stripe">
    <thead class="thead-light">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th>Shipping Address</th>
            <th>Order Note</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shipmentQueues as $queue)
            <tr>
                <td>{{ $queue->order_id }}</td>
                <td>{{ optional($queue->order)->customer_name ?? 'N/A' }}</td>
                <td>{{ optional($queue->order)->phone_number ?? 'N/A' }}</td>
                <td>{{ optional($queue->order)->shipping_address ?? 'N/A' }}</td>
                <td>{{ optional($queue->order)->order_note ?? '-' }}</td>
                <td>
                    <form action="{{ route('shipment-queues.update', $queue->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control" onchange="this.form.submit()" required>
                            @php
                                $statuses = ['In queue', 'Shipping', 'Done', 'Rejected'];
                            @endphp
                            @foreach ($statuses as $status)
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
