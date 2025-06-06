@extends('parts.main')

@section('pageHeader', 'Shipment Queue')

@section('main-section')
<div class="table-responsive">
    <table class="table table-bordered table-hover stripe">
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
                @php
                    // Define status colors
                    $statusColors = [
                        'In queue' => 'status-queue',  // Purple
                        'Shipping' => 'status-progress', // Blue
                        'Done' => 'status-success',    // Green
                        'Rejected' => 'status-rejected' // Red
                    ];

                    // Determine the current status color class
                    $statusClass = $statusColors[$queue->status] ?? 'status-queue'; // Default to purple (In Queue)
                @endphp
                <tr>
                    <td>{{ $queue->order_id }}</td>
                    <td>{{ optional($queue->order)->customer_name ?? 'N/A' }}</td>
                    <td>{{ optional($queue->order)->phone_number ?? 'N/A' }}</td>
                    <td>{{ optional($queue->order)->shipping_address ?? 'N/A' }}</td>
                    <td>{{ optional($queue->order)->order_note ?? '-' }}</td>

                    <!-- Status Column with color class and form for updating status -->
                    <td>
                        <form action="{{ route('shipment-queues.update', $queue->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control {{ $statusClass }}" onchange="this.form.submit()" required>
                                @php
                                    $statuses = ['In queue', 'Shipping', 'Done', 'Rejected'];
                                @endphp
                                @foreach ($statuses as $status)
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
