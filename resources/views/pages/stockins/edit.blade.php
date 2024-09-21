@extends('layouts.app')

@section('content')
<div class="container mt-5" style="padding-top: 80px;">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Edit Stock In</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('stock-ins.update', $stockIn->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $stockIn->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="stock_in_date" class="form-label">Stock In Date</label>
                        <input type="date" name="stock_in_date" class="form-control" value="{{ $stockIn->stock_in_date->format('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="batch_no" class="form-label">Batch Number</label>
                        <input type="text" name="batch_no" class="form-control" value="{{ $stockIn->batch_no }}">
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="text-secondary">Stock In Items</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="items-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 30%;">Product</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Price per Unit</th>
                                            <th>Discount</th>
                                            <th>Discount Type</th>
                                            <th style="width: 10%;">Expiration Date</th>
                                            <th style="width: 10%;">Manufacturing Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stockIn->items as $index => $item)
                                        <tr>
                                            <td>
                                                <select name="items[{{ $index }}][product_id]" class="form-control" required>
                                                    <option value="">-- Select Product --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" required></td>
                                            <td><input type="text" name="items[{{ $index }}][unit]" class="form-control" value="{{ $item->unit }}" required></td>
                                            <td><input type="number" name="items[{{ $index }}][price_per_unit]" class="form-control" value="{{ $item->price_per_unit }}" required></td>
                                            <td><input type="number" name="items[{{ $index }}][discount]" class="form-control" value="{{ $item->discount }}"></td>
                                            <td>
                                                <select name="items[{{ $index }}][discount_type]" class="form-control">
                                                    <option value="amount" {{ $item->discount_type == 'amount' ? 'selected' : '' }}>Amount</option>
                                                    <option value="percentage" {{ $item->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                </select>
                                            </td>
                                            <td><input type="date" name="items[{{ $index }}][expiration_date]" class="form-control" value="{{ $item->expiration_date }}"></td>
                                            <td><input type="date" name="items[{{ $index }}][manufacturing_date]" class="form-control" value="{{ $item->manufacturing_date }}"></td>
                                            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-success mt-2" id="add-item">Add Item</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Stock In</button>
            </form>
        </div>
    </div>
</div>

<script>
    let itemIndex = {{ count($stockIn->items) }}; // Set itemIndex to the count of existing items

    document.getElementById('add-item').addEventListener('click', function() {
        const tableBody = document.querySelector('#items-table tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="form-control" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control" required></td>
            <td><input type="text" name="items[${itemIndex}][unit]" class="form-control" required></td>
            <td><input type="number" name="items[${itemIndex}][price_per_unit]" class="form-control" required></td>
            <td><input type="number" name="items[${itemIndex}][discount]" class="form-control"></td>
            <td>
                <select name="items[${itemIndex}][discount_type]" class="form-control">
                    <option value="amount">Amount</option>
                    <option value="percentage">Percentage</option>
                </select>
            </td>
            <td><input type="date" name="items[${itemIndex}][expiration_date]" class="form-control"></td>
            <td><input type="date" name="items[${itemIndex}][manufacturing_date]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
        `;
        tableBody.appendChild(newRow);
        itemIndex++;
    });

    document.querySelector('#items-table').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
        }
    });
</script>

@endsection
