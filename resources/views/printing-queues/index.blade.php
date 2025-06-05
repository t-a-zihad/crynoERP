@extends('parts.main')

@section('pageHeader', 'Print Queue')
@section('main-section')
<div class="table-responsive">
<table class="table table-bordered table-hover stripe">
        <thead class="thead-light">
            <tr>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Quantity</th>
                <th>Priority</th>
                <th>Special Note</th>
                <th>PDF Link</th>
                <th>Cover Print Status</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $book = $item->orderedBook;
                    $order = $book->order ?? null;
                @endphp
                <tr>
                    <td>{{ $item->ordered_book_id }}</td>
                    <td>{{ $book->book_name ?? 'N/A' }}</td>
                    <td>{{ $book->qty ?? 'N/A' }}</td>
                    <td>{{ $order->order_priority ?? 'N/A' }}</td>
                    <td>{{ $book->special_note ?? '-' }}</td>
                    <td>
                        @if($book->pdf_link)
                            <a href="{{ $book->pdf_link }}" target="_blank">View PDF</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->cover_printing_status }}</td>
                    <td>
                        <form action="{{ route('printing-queues.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
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
@endsection

