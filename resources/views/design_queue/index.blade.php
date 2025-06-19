@extends('parts.main')

@section('pageHeader', 'Design Queue')

@section('main-section')
<div class="table-responsive">
    <!-- Bulk Update Form -->
    <form method="POST" action="{{ route('design-queues.bulk-update') }}">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds" value="[]">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <select name="bulk_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="In Queue">In Queue</option>
                    <option value="Pre-designed">Pre-designed</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Done">Done</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Selected</button>
        </div>
    </form>

        <table class="table table-bordered table-hover stripe">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Ordered Book ID</th>
                    <th>Book Name</th>
                    <th>Qty</th>
                    <th>Priority</th>
                    <th>Binding Type</th>
                    <th>Lamination Type</th>
                    <th>Special Note</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($designQueues as $designQueue)
                <tr>
                    <td><input type="checkbox" class="row-checkbox" value="{{ $designQueue->id }}"></td>
                    <td>{{ $designQueue->ordered_book_id }}</td>
                    <td>{{ $designQueue->orderedBook->book_name ?? 'N/A' }}</td>
                    <td>{{ $designQueue->orderedBook->qty ?? 'N/A' }}</td>

                    @php
                        $priorityColors = [
                            'High' => 'priority-high',
                            'Normal' => 'priority-normal',
                            'Low' => 'priority-low'
                        ];
                        $priorityClass = $priorityColors[$designQueue->orderedBook->order->order_priority] ?? 'text-muted';

                        $statuses = ['In Queue', 'Pre-designed', 'In Progress', 'Done', 'Rejected'];
                        $statusColors = [
                            'In Queue' => 'status-queue',
                            'Pre-designed' => 'status-progress',
                            'In Progress' => 'status-progress',
                            'Done' => 'status-success',
                            'Rejected' => 'status-rejected'
                        ];
                        $statusClass = $statusColors[$designQueue->status] ?? 'status-queue';
                    @endphp

                    <td><span class="btn btn-sm {{ $priorityClass }}">{{ $designQueue->orderedBook->order->order_priority ?? 'N/A' }}</span></td>
                    <td>{{ $designQueue->orderedBook->binding_type }}</td>
                    <td>{{ $designQueue->orderedBook->lamination_type }}</td>
                    <td>{{ $designQueue->orderedBook->special_note ?? '-' }}</td>
                    <td>
                        <form action="{{ route('design-queues.update', $designQueue->id) }}" method="POST" class="form-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="order_id" value="{{$designQueue->order_id}}">
                            <input type="hidden" name="ordered_book_id" value="{{$designQueue->ordered_book_id}}">
                            <select name="status" class="form-control {{ $statusClass }}" required onchange="this.form.submit()">
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

</div>

<script>
    const selectedIdsInput = document.getElementById('selectedIds');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');

    function updateSelectedIds() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        selectedIdsInput.value = JSON.stringify(selected);
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedIds));
    selectAllCheckbox.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        updateSelectedIds();
    });
</script>

@endsection
