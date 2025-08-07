@extends('parts.main')

@section('pageHeader', 'Shipment Queue')

@section('main-section')
<div class="table-responsive">
    <form method="POST" action="{{ route('shipment-queues.bulk-update') }}">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds" value="[]">
        <Input name="bulk_status" type="hidden" value="Shipped" required></Input>

        <div class="d-flex justify-content-between mb-3">
            <button type="submit" class="btn btn-primary">Send the Selected For Courier</button>
        </div>
    </form>

        <table class="table table-bordered table-hover stripe">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Shipping Address</th>
                    <th>Order Note</th>
                    <th>Status</th>
                    <th>Courier Status & Tracking</th>
                    <th>Consignment ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shipmentQueues as $queue)
                    @php
                        $statusColors = [
                            'In queue' => 'status-queue',
                            'Shipping' => 'status-progress',
                            'Done' => 'status-success',
                            'Shipped' => 'status-success',
                            'Rejected' => 'status-rejected'
                        ];
                        $statusClass = $statusColors[$queue->status] ?? 'status-queue';


                    @endphp
                    <tr>
                        <td><input type="checkbox" class="{{$queue->status == 'Shipped' || $queue->status == 'In Progress' ? 'disabled' : 'row-checkbox'}}" value="{{$queue->id}}" {{$queue->status == 'Shipped' || $queue->status == 'In Progress' ? 'disabled' : ''}}></td>
                        <td>{{ $queue->order_id }}</td>
                        <td>{{ optional($queue->order)->customer_name ?? 'N/A' }}</td>
                        <td>{{ optional($queue->order)->phone_number ?? 'N/A' }}</td>
                        <td>{{ optional($queue->order)->shipping_address ?? 'N/A' }}</td>
                        <td>{{ optional($queue->order)->order_note ?? '-' }}</td>
                        <td>
                            @if($queue->status == 'In queue')
                            <form action="{{ route('shipment-queues.update', $queue->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <Input name="status" type="hidden" value="Shipped"></Input>
                                <Input type="submit" value="Send To Courier" class="btn {{$statusClass}}"></Input>
                            </form>
                            @elseif ($queue->status == 'In Progress')
                            <form action="{{ route('shipment-queues.update', $queue->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <Input name="status" type="hidden" value="In queue"></Input>
                                <Input type="submit" value="Mark as In queue" data-toggle="tooltip" title="Check All The Books Have Been Packed!" class="btn {{$statusClass}}"></Input>
                            </form>
                            @elseif ($queue->status == 'Shipped')
                                <button class="btn {{$statusClass}}">Sending Done</button>
                            @endif
                        </td>
                        <td>
                            @if ($queue->tracking_code)
                                <a href="https://steadfast.com.bd/t/{{$queue->tracking_code}}" data-toggle="tooltip" title="{{$queue->detailedStatus}}" class="btn btn-secondary" target="_blank">{{$queue->shortStatus}}</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $queue->consignment_id ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</div>

<script>
    const selectedIdsInput = document.getElementById('selectedIds');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');

    function updateSelectedIds() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        selectedIdsInput.value = JSON.stringify(selected);
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedIds));
    selectAllCheckbox.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        updateSelectedIds();
    });
</script>
@endsection
