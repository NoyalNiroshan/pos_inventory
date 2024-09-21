@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 90px;"
>
    <h1>Supplier Stock Payments</h1>

    <table class="table table-bordered">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>Stock In ID</th>
                <th>Supplier</th>
                <th>Amount Paid</th>
                <th>Balance Due</th>
                <th>Payment Method</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->stockIn->id }}</td>
                <td>{{ $payment->stockIn->supplier->company_name }}</td>
                <td>LKR{{ number_format($payment->amount_paid, 2) }}</td>
                <td>LKR{{ number_format($payment->balance_due, 2) }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>

                <td>
                    <!-- View Button -->
                    <a href="{{ route('supplier-stock-payments.show', $payment->id) }}" class="btn btn-info btn-sm">View</a>

                    <!-- Delete Form -->
                    <form action="{{ route('supplier-stock-payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
