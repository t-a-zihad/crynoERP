@extends('parts.main')

@section('pageHeader', 'Print Queue')

@section('main-section')
<div class="table-responsive">
    <form method="POST" action="{{ route('printing-queues.bulk-update') }}">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds" value="[]">

        <div class="d-flex justify-content-between mb-3">
            <select name="bulk_status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="In Queue">In Queue</option>
                <option value="In Progress">In Progress</option>
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
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Quantity</th>
                    <th>Priority</th>
                    <th>Special Note</th>
                    <th>PDF Link</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    @php
                        $book = $item->orderedBook;
                        $order = $book->order ?? null;

                        $priorityColors = [
                            'High' => 'priority-high',
                            'Normal' => 'priority-normal',
                            'Low' => 'priority-low'
                        ];
                        $priorityClass = $priorityColors[$order->order_priority] ?? 'text-muted';

                        $statusColors = [
                            'In Queue' => 'status-queue',
                            'In Progress' => 'status-progress',
                            'Rendered' => 'status-progress',
                            'Done' => 'status-success',
                            'Rejected' => 'status-rejected'
                        ];
                        $statusClass = $statusColors[$item->status] ?? 'status-queue';
                    @endphp
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $item->id }}"></td>
                        <td>{{ $item->ordered_book_id }}</td>
                        <td>{{ $book->book_name ?? 'N/A' }}</td>
                        <td>{{ $book->qty ?? 'N/A' }}</td>
                        <td><span class="btn btn-sm {{ $priorityClass }}">{{ $order->order_priority ?? 'N/A' }}</span></td>
                        <td>{{ $book->special_note ?? '-' }}</td>
                        <td>
                            @if($book->pdf_link)
                                <a href="{{ $book->pdf_link }}" target="_blank">View PDF</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('printing-queues.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control form-control-sm {{ $statusClass }}" onchange="this.form.submit()">
                                    @foreach(['In Queue', 'In Progress', 'Done', 'Rejected'] as $status)
                                        <option value="{{ $status }}" {{ $item->status == $status ? 'selected' : '' }}>
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
