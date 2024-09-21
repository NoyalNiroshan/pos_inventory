@extends('layouts.app')

@section('content')
<div class="container mt-5" style="padding-top: 80px;">
    <h2>Stock In Details</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Stock In Information</div>
        <div class="card-body">
            <p><strong>Supplier: </strong>{{ $stockIn->supplier->name }}</p>
            <p><strong>Stock In Date: </strong>{{ $stockIn->stock_in_date->format('Y-m-d') }}</p>
            <p><strong>Batch Number: </strong>{{ $stockIn->batch_no }}</p>
            <p><strong>Total Amount: </strong>${{ number_format($stockIn->total_amount, 2) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">Stock In Items</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price per Unit</th>
                        <th>Discount</th>
                        <th>Discount Type</th>
                        <th>Total Price</th>
                        <th>Expiration Date</th>
                        <th>Manufacturing Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockIn->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>${{ number_format($item->price_per_unit, 2) }}</td>
                        <td>{{ $item->discount }}</td>
                        <td>{{ ucfirst($item->discount_type) }}</td>
                        <td>LKR{{ number_format($item->total_price, 2) }}</td>
                        <td>{{ $item->expiration_date }}</td>
                        <td>{{ $item->manufacturing_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
