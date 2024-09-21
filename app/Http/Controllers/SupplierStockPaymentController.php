<?php

namespace App\Http\Controllers;

use App\Models\SupplierStockPayment;
use App\Models\StockIn;
use Illuminate\Http\Request;

class SupplierStockPaymentController extends Controller
{
    // Show form to create a payment for a specific StockIn
    public function create($stockInId)
    {
        $stockIn = StockIn::with('supplier')->findOrFail($stockInId);
        return view('pages.payments.create', compact('stockIn'));
    }

    // Store the new payment
    public function store(Request $request, $stockInId)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $stockIn = StockIn::with('payments')->findOrFail($stockInId);
        $balanceDue = $stockIn->total_amount - $stockIn->payments->sum('amount_paid');
        $amountPaid = $request->input('amount_paid');

        // Check if the paid amount exceeds the balance due
        if ($amountPaid > $balanceDue) {
            return redirect()->back()->with('error', 'The paid amount exceeds the remaining balance.');
        }

        // Create the new payment record
        SupplierStockPayment::create([
            'stock_in_id' => $stockIn->id,
            'amount_paid' => $amountPaid,
            'balance_due' => $balanceDue - $amountPaid,
            'payment_method' => $request->input('payment_method'),
            'payment_date' => now(),
        ]);

        return redirect()->route('stock-ins.index')->with('success', 'Payment recorded successfully.');
    }

    // Display all supplier stock payments
    public function index()
    {
        $payments = SupplierStockPayment::with('stockIn.supplier')->get();
        return view('pages.payments.index', compact('payments'));
    }
}
