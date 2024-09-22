@extends('layouts.app')

@section('content')
<div class="container mt-5" style="padding-top: 80px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Stock In List</h1>
        <a href="{{ route('stock-ins.create') }}" class="btn btn-primary">Create New Stock In</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="bg-primary text-white">
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Stock In Date</th>
                    <th>Batch No</th>
                    <th>Total Amount</th>
                    <th>Balance Due</th> <!-- New Balance Due Column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockIns as $stockIn)
                <tr>
                    <td>{{ $stockIn->id }}</td>
                    <td>{{ $stockIn->supplier->company_name }}</td>
                    <td>{{ $stockIn->stock_in_date->format('Y-m-d') }}</td>
                    <td>{{ $stockIn->batch_no }}</td>
                    <td>LKR{{ number_format($stockIn->total_amount, 2) }}</td>
                    <td>LKR{{ number_format($stockIn->balance_due, 2) }}</td> <!-- Show Balance Due -->
                    <td>
                        <a href="{{ route('supplier-stock-payments.create', $stockIn->id) }}" class="btn btn-sm btn-success">
                            Pay
                        </a>
                        <a href="{{ route('stock-ins.show', $stockIn->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('stock-ins.edit', $stockIn->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('stock-ins.destroy', $stockIn->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this stock in?');">
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('stock-in.generate-invoice', $stockIn->id) }}" class="btn btn-sm btn-primary">
                            Download Invoice
                        </a>


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
