@extends('parts.main')

@section('pageHeader', 'Packaging Queue')

@section('main-section')
<div class="table-responsive">
    <form method="POST" action="{{ route('packaging-queues.bulk-update') }}">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds" value="[]">

        <div class="d-flex justify-content-between mb-3">
            <select name="bulk_status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="In queue">In queue</option>
                <option value="Done">Done</option>
                <option value="Rejected">Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary">Update Selected</button>
        </div>
    </form>

        <table class="table table-bordered table-hover  stripe">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
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
                        $orderPriority = $books->first()?->order->order_priority ?? 'N/A';
                        $orderNote = $books->first()?->order->order_note ?? 'N/A';

                        $priorityColors = [
                            'High' => 'priority-high',
                            'Normal' => 'priority-normal',
                            'Low' => 'priority-low'
                        ];

                        $statusColors = [
                            'In queue' => 'status-queue',
                            'Done' => 'status-success',
                            'Rejected' => 'status-rejected'
                        ];

                        $priorityClass = $priorityColors[$orderPriority] ?? 'text-muted';
                        $statusClass = $statusColors[$queue->status] ?? 'status-queue';
                    @endphp

                    @foreach($books as $index => $book)
                        <tr>
                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}">
                                    <input type="checkbox" class="row-checkbox" value="{{ $queue->id }}">
                                </td>
                                <td rowspan="{{ $rowCount }}">{{ $queue->order_id }}</td>
                            @endif
                            <td>{{ $book->ordered_book_id }}</td>
                            <td>{{ $book->book_name }}</td>
                            <td>{{ $book->qty }}</td>

                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}">
                                    <span class="btn btn-sm {{ $priorityClass }}">{{ $orderPriority }}</span>
                                </td>
                            @endif

                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}">{{ $orderNote }}</td>
                            @endif

                            @if($index === 0)
                                <td rowspan="{{ $rowCount }}">
                                    <form action="{{ route('packaging-queues.update', $queue->id) }}" method="POST" class="form-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control {{ $statusClass }}" onchange="this.form.submit()">
                                            @foreach(['In queue', 'Done', 'Rejected'] as $statusOption)
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
