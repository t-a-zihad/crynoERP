@extends('parts.main')

@section('pageHeader', 'Shipment Queue')

@section('main-section')
<div class="table-responsive">
    <form method="POST" action="{{ route('shipment-queues.bulk-update') }}">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds" value="[]">

        <div class="d-flex justify-content-between mb-3">
            <select name="bulk_status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="In queue">In queue</option>
                <option value="Shipping">Shipping</option>
                <option value="Done">Done</option>
                <option value="Rejected">Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary">Update Selected</button>
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
                </tr>
            </thead>
            <tbody>
                @foreach ($shipmentQueues as $queue)
                    @php
                        $statusColors = [
                            'In queue' => 'status-queue',
                            'Shipping' => 'status-progress',
                            'Done' => 'status-success',
                            'Rejected' => 'status-rejected'
                        ];
                        $statusClass = $statusColors[$queue->status] ?? 'status-queue';
                    @endphp
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $queue->id }}"></td>
                        <td>{{ $queue->order_id }}</td>
                        <td>{{ optional($queue->order)->customer_name ?? 'N/A' }}</td>
                        <td>{{ optional($queue->order)->phone_number ?? 'N/A' }}</td>
                        <td>{{ optional($queue->order)->shipping_address ?? 'N/A' }}</td>
                        <td>{{ optional($queue->order)->order_note ?? '-' }}</td>
                        <td>
                            <form action="{{ route('shipment-queues.update', $queue->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control {{ $statusClass }}" onchange="this.form.submit()" required>
                                    @foreach (['In queue', 'Shipping', 'Done', 'Rejected'] as $status)
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
