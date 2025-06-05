@extends('parts.main')

@section('pageHeader', 'All Books')

@section('main-section')
<div class="table-responsive">
<table class="table table-bordered table-hover stripe">
    <thead class="thead-light">
        <tr>
            <th>Date</th>
            <th>Order ID</th>
            <th>Book ID</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Book Name</th>
            <th>Book Author</th>
            <th>Binding Type</th>
            <th>Special Note</th>
            <th>Book PDF</th>
            <th>Custom Cover</th>
            <th>Cover</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Design Status</th>
            <th>Printing Status</th>
            <th>Cover Printing Status</th>
            <th>Binding Status</th>
            <th>QC Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
            @php
                $statuses = [
                    optional($book->designQueue)->status ?? 'In Queue',
                    optional($book->printingQueue)->status ?? 'In Queue',
                    optional($book->coverPrintingQueue)->status ?? 'In Queue',
                    optional($book->bindingQueue)->status ?? 'In Queue',
                    optional($book->qcQueue)->status ?? 'In Queue',
                ];

                // Normalize statuses to lowercase for comparison
                $lowerStatuses = array_map('strtolower', $statuses);

                if (count(array_unique($lowerStatuses)) === 1 && $lowerStatuses[0] === 'done') {
                    $overallStatus = 'Done';
                } elseif (count(array_unique($lowerStatuses)) === 1 && $lowerStatuses[0] === 'in queue') {
                    $overallStatus = 'In Queue';
                } else {
                    $overallStatus = 'In Progress';
                }

                $price = $book->unit_price * $book->qty;
            @endphp
            <tr>
                <td>{{ $book->created_at->format('Y-m-d') }}</td>
                <td>{{ $book->order_id }}</td>
                <td>{{ $book->ordered_book_id }}</td>
                <td>{{ optional($book->order)->order_priority ?? 'N/A' }}</td>
                <td>{{ $overallStatus }}</td>
                <td>{{ $book->book_name }}</td>
                <td>{{ $book->book_author ?? '-' }}</td>
                <td>{{ $book->binding_type }}</td>
                <td>{{ $book->special_note ?? '-' }}</td>
                <td>
                    @if($book->pdf_link)
                        <a href="{{ $book->pdf_link }}" target="_blank">PDF</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $book->custom_cover ? 'Yes' : 'No' }}</td>
                <td>
                    @if($book->cover_link)
                        <a href="{{ $book->cover_link }}" target="_blank">Cover</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ number_format($book->unit_price, 2) }}</td>
                <td>{{ $book->qty }}</td>
                <td>{{ number_format($price, 2) }}</td>
                <td>{{ optional($book->designQueue)->status ?? 'In Queue' }}</td>
                <td>{{ optional($book->printingQueue)->status ?? 'In Queue' }}</td>
                <td>{{ optional($book->coverPrintingQueue)->status ?? 'In Queue' }}</td>
                <td>{{ optional($book->bindingQueue)->status ?? 'In Queue' }}</td>
                <td>{{ optional($book->qcQueue)->status ?? 'In Queue' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
