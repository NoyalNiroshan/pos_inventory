@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 90px;" >
    <h1>Payment Details for Payment #{{ $payment->id }}</h1>

    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            Payment Information
        </div>
        <div class="card-body">
            <p><strong>Stock In ID:</strong> {{ $payment->stockIn->id }}</p>
            <p><strong>Supplier:</strong> {{ $payment->stockIn->supplier->company_name }}</p>
            <p><strong>Amount Paid:</strong> ${{ number_format($payment->amount_paid, 2) }}</p>
            <p><strong>Balance Due:</strong> ${{ number_format($payment->balance_due, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date->format('Y-m-d') }}</p>
        </div>
    </div>

    <a href="{{ route('supplier-stock-payments.index') }}" class="btn btn-secondary mt-3">Back to Payments</a>
</div>
@endsection
