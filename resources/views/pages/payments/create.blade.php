@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 90px;"
>
    <h1>Make Payment for Stock In #{{ $stockIn->id }}</h1>

    <form action="{{ route('supplier-stock-payments.store', $stockIn->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="amount_paid" class="form-label">Amount to Pay</label>
            <input type="number" name="amount_paid" class="form-control" required step="0.01" max="{{ $stockIn->total_amount - $stockIn->payments->sum('amount_paid') }}">
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="credit">Credit</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Payment</button>
    </form>
</div>
@endsection
