@extends('layouts.app')

@section('content')
<div class="container mt-5" style="padding-top: 90px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Create Stock In</h1>
        <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary">Stock In List</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stock-ins.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="stock_in_date" class="form-label">Stock In Date</label>
                        <input type="date" name="stock_in_date" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="batch_no" class="form-label">Batch Number</label>
                        <input type="text" name="batch_no" class="form-control">
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5>Stock In Items</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="items-table">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Price per Unit</th>
                                    <th>Discount</th>
                                    <th>Discount Type</th>
                                    <th>Expiration Date</th>
                                    <th>Manufacturing Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="items[0][product_id]" class="form-control product-select" data-index="0" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[0][quantity]" class="form-control" required></td>
                                    <td>
                                        <select name="items[0][unit]" class="form-control unit-select" data-index="0" required>
                                            <option value="">-- Select Unit --</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[0][price_per_unit]" class="form-control" required></td>
                                    <td><input type="number" name="items[0][discount]" class="form-control"></td>
                                    <td>
                                        <select name="items[0][discount_type]" class="form-control">
                                            <option value="amount">Amount</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </td>
                                    <td><input type="date" name="items[0][expiration_date]" class="form-control"></td>
                                    <td><input type="date" name="items[0][manufacturing_date]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Create Stock In</button>
            </form>
        </div>
    </div>
</div>

<script>
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', function() {
        const tableBody = document.querySelector('#items-table tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="form-control product-select" data-index="${itemIndex}" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control" required></td>
            <td>
                <select name="items[${itemIndex}][unit]" class="form-control unit-select" data-index="${itemIndex}" required>
                    <option value="">-- Select Unit --</option>
                </select>
            </td>
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

    document.querySelector('#items-table').addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('product-select')) {
            const index = e.target.dataset.index;
            const productId = e.target.value;

            fetch(`/products/${productId}/units`)
                .then(response => response.json())
                .then(units => {
                    const unitSelect = document.querySelector(`select[name="items[${index}][unit]"]`);
                    unitSelect.innerHTML = '';
                    units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit;
                        option.text = unit;
                        unitSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching units:', error));
        }
    });
</script>

@endsection
