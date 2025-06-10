@extends('parts.main')

@section('pageHeader', 'Edit Order')

@section('main-section')
<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('orders.update', $order->order_id) }}">
        @csrf
        @method('PUT')

        <h4>Order Details</h4>

        <div class="form-group">
            <label for="order_priority">Order Priority</label>
            <select id="order_priority" name="order_priority" class="form-control @error('order_priority') is-invalid @enderror" required>
                <option value="">Select Priority</option>
                <option value="High" {{ old('order_priority', $order->order_priority) == 'High' ? 'selected' : '' }}>High</option>
                <option value="Normal" {{ old('order_priority', $order->order_priority) == 'Normal' ? 'selected' : '' }}>Normal</option>
                <option value="Low" {{ old('order_priority', $order->order_priority) == 'Low' ? 'selected' : '' }}>Low</option>
            </select>
            @error('order_priority')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="customer_name">Customer Name</label>
            <input id="customer_name" name="customer_name" type="text" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $order->customer_name) }}" required>
            @error('customer_name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input id="phone_number" name="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $order->phone_number) }}" required>
            @error('phone_number')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="shipping_address">Shipping Address</label>
            <textarea id="shipping_address" name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
            @error('shipping_address')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="delivery_type">Delivery Type</label>
            <select id="delivery_type" name="delivery_type" class="form-control @error('delivery_type') is-invalid @enderror" required>
                <option value="">Select Delivery Type</option>
                <option value="Free" {{ old('delivery_type', $order->delivery_type) == 'Free' ? 'selected' : '' }}>Free</option>
                <option value="Inside Dhaka" {{ old('delivery_type', $order->delivery_type) == 'Inside Dhaka' ? 'selected' : '' }}>Inside Dhaka</option>
                <option value="Outside Dhaka" {{ old('delivery_type', $order->delivery_type) == 'Outside Dhaka' ? 'selected' : '' }}>Outside Dhaka</option>
            </select>
            @error('delivery_type')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="discount">Discount</label>
            <input id="discount" name="discount" type="number" step="0.01" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $order->discount) }}">
            @error('discount')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="order_note">Order Note</label>
            <textarea id="order_note" name="order_note" class="form-control @error('order_note') is-invalid @enderror">{{ old('order_note', $order->order_note) }}</textarea>
            @error('order_note')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="handled_by">Handled By (Order Manager)</label>
            <select id="handled_by" name="handled_by" class="form-control @error('handled_by') is-invalid @enderror" required>
                <option value="">Select Manager</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ old('handled_by', $order->handled_by) == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                @endforeach
            </select>
            @error('handled_by')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <hr>
        <h4>Books</h4>
        <div id="books-wrapper">
            @foreach(old('books', $order->orderedBooks->toArray()) as $index => $book)
            <div class="book-item border p-3 mb-3">
                <input type="hidden" name="books[{{ $index }}][ordered_book_id]" value="{{ $book['ordered_book_id'] ?? '' }}">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Book Name</label>
                        <input type="text" name="books[{{ $index }}][book_name]" class="form-control @error('books.' . $index . '.book_name') is-invalid @enderror" value="{{ $book['book_name'] ?? '' }}" required>
                        @error('books.' . $index . '.book_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Book Author</label>
                        <input type="text" name="books[{{ $index }}][book_author]" class="form-control @error('books.' . $index . '.book_author') is-invalid @enderror" value="{{ $book['book_author'] ?? '' }}">
                        @error('books.' . $index . '.book_author')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Binding Type</label>
                        <select name="books[{{ $index }}][binding_type]" class="form-control @error('books.' . $index . '.binding_type') is-invalid @enderror" required>
                            <option value="Paperback" {{ (isset($book['binding_type']) && $book['binding_type'] == 'Paperback') ? 'selected' : '' }}>Paperback</option>
                            <option value="Hardcover" {{ (isset($book['binding_type']) && $book['binding_type'] == 'Hardcover') ? 'selected' : '' }}>Hardcover</option>
                            <option value="Spiral" {{ (isset($book['binding_type']) && $book['binding_type'] == 'Spiral') ? 'selected' : '' }}>Spiral</option>
                        </select>
                        @error('books.' . $index . '.binding_type')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Unit Price</label>
                        <input type="number" step="0.01" name="books[{{ $index }}][unit_price]" class="form-control @error('books.' . $index . '.unit_price') is-invalid @enderror" value="{{ $book['unit_price'] ?? '' }}" required>
                        @error('books.' . $index . '.unit_price')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Quantity</label>
                        <input type="number" name="books[{{ $index }}][qty]" class="form-control @error('books.' . $index . '.qty') is-invalid @enderror" value="{{ $book['qty'] ?? 1 }}" required>
                        @error('books.' . $index . '.qty')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Special Note</label>
                    <textarea name="books[{{ $index }}][special_note]" class="form-control @error('books.' . $index . '.special_note') is-invalid @enderror">{{ $book['special_note'] ?? '' }}</textarea>
                    @error('books.' . $index . '.special_note')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>PDF Link</label>
                    <input type="url" name="books[{{ $index }}][pdf_link]" class="form-control @error('books.' . $index . '.pdf_link') is-invalid @enderror" value="{{ $book['pdf_link'] ?? '' }}">
                    @error('books.' . $index . '.pdf_link')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Custom Cover</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="books[{{ $index }}][custom_cover]" value="1" class="form-check-input" id="custom_cover_yes_{{ $index }}" {{ (isset($book['custom_cover']) && $book['custom_cover'] == 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="custom_cover_yes_{{ $index }}">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="books[{{ $index }}][custom_cover]" value="0" class="form-check-input" id="custom_cover_no_{{ $index }}" {{ (!isset($book['custom_cover']) || $book['custom_cover'] == 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="custom_cover_no_{{ $index }}">No</label>
                    </div>
                    @error('books.' . $index . '.custom_cover')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Cover Link</label>
                    <input type="url" name="books[{{ $index }}][cover_link]" class="form-control @error('books.' . $index . '.cover_link') is-invalid @enderror" value="{{ $book['cover_link'] ?? '' }}">
                    @error('books.' . $index . '.cover_link')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <input type="text" name="books[{{ $index }}][status]" class="form-control" value="{{ $book['status'] ?? 'in queue' }}" readonly>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addBook()">Add Another Book</button>

        <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
</div>

<script>
    let bookIndex = {{ count(old('books', $order->orderedBooks)) }};
    function addBook() {
        const wrapper = document.getElementById('books-wrapper');
        const firstBook = document.querySelector('.book-item');
        const newBook = firstBook.cloneNode(true);

        newBook.querySelectorAll('input, select, textarea').forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace(/\d+/, bookIndex);

                if (input.type === 'radio') {
                    input.checked = false;
                } else if(input.readOnly){
                    input.value = 'in queue'; // default status
                } else {
                    input.value = '';
                }
            }
        });

        // Fix radio button ids & labels
        newBook.querySelectorAll('input[type=radio]').forEach((input, idx) => {
            const newId = input.name.replace(/\[|\]/g, '') + '_' + idx;
            input.id = newId;
            const label = input.nextElementSibling;
            if(label && label.tagName === 'LABEL') {
                label.htmlFor = newId;
            }
        });

        wrapper.appendChild(newBook);
        bookIndex++;
    }
</script>
@endsection
