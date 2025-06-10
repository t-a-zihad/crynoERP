@extends('parts.main')

@section('pageHeader', 'Cover Printing Queue')

@section('main-section')
<div class="table-responsive">
    <form method="POST" action="{{ route('cover-printing-queues.bulk-update') }}">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds" value="[]">

        <div class="d-flex justify-content-between mb-3">
            <select name="bulk_status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="In Queue">In Queue</option>
                <option value="Rendered">Rendered</option>
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
                    <th>Ordered Book ID</th>
                    <th>Book Name</th>
                    <th>Qty</th>
                    <th>Priority</th>
                    <th>Special Note</th>
                    <th>Cover</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($queues as $queue)
                    @php
                        $priorityColors = [
                            'High' => 'priority-high',
                            'Normal' => 'priority-normal',
                            'Low' => 'priority-low'
                        ];
                        $priorityClass = $priorityColors[$queue->orderedBook->order->order_priority] ?? 'text-muted';

                        $statusColors = [
                            'In Queue' => 'status-queue',
                            'Rendered' => 'status-progress',
                            'In Progress' => 'status-progress',
                            'Done' => 'status-success',
                            'Rejected' => 'status-rejected'
                        ];
                        $statusClass = $statusColors[$queue->status] ?? 'status-queue';
                        $pStatusClass = $statusColors[$queue->printing_status] ?? 'status-queue';
                    @endphp

                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $queue->id }}"></td>
                        <td>{{ $queue->ordered_book_id }}</td>
                        <td>{{ $queue->orderedBook->book_name ?? 'N/A' }}</td>
                        <td>{{ $queue->orderedBook->qty ?? 'N/A' }}</td>
                        <td><span class="btn btn-sm {{ $priorityClass }}">{{ $queue->orderedBook->order->order_priority ?? 'N/A' }}</span></td>
                        <td>{{ $queue->orderedBook->special_note ?? '-' }}</td>
                        <td>
                            @if(!empty($queue->orderedBook->cover))
                                <a href="{{ $queue->orderedBook->cover }}" target="_blank">View Cover</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('cover-printing-queues.update', $queue->id) }}" method="POST" class="form-inline">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control {{ $statusClass }}" required onchange="this.form.submit()">
                                    @foreach(['In Queue', 'Rendered', 'Done', 'Rejected'] as $status)
                                        <option value="{{ $status }}" {{ $queue->status == $status ? 'selected' : '' }}>
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
