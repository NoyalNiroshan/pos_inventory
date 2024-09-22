<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Product;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StockInController extends Controller
{
    // List all StockIns
    public function index()
    {
        // Fetch all stock-ins with their suppliers and payments
        $stockIns = StockIn::with('supplier', 'payments')->get();

        // Calculate balance due for each stock-in
        foreach ($stockIns as $stockIn) {
            $paidAmount = $stockIn->payments->sum('amount_paid');
            $stockIn->balance_due = $stockIn->total_amount - $paidAmount;
        }

        return view('pages.stockins.index', compact('stockIns'));
    }


    // Show the Create StockIn form
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('pages.stockins.create', compact('suppliers', 'products'));
    }

    // Store StockIn and its items
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_in_date' => 'required|date',
            'batch_no' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
            'items.*.price_per_unit' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.discount_type' => 'nullable|in:amount,percentage',
            'items.*.expiration_date' => 'nullable|date',
            'items.*.manufacturing_date' => 'nullable|date'
        ]);

        // Create the StockIn record
        $stockIn = StockIn::create([
            'supplier_id' => $validated['supplier_id'],
            'stock_in_date' => $validated['stock_in_date'],
            'batch_no' => $validated['batch_no'],
        ]);

        $totalAmount = 0;

        // Loop through the items and create each StockInItem
        foreach ($validated['items'] as $item) {
            // Calculate the total price before discount for this item (quantity * price_per_unit)
            $totalPriceBeforeDiscount = $item['quantity'] * $item['price_per_unit'];

            // Apply discount based on discount type
            if ($item['discount_type'] === 'percentage') {
                // Apply percentage discount
                $discountedPrice = $totalPriceBeforeDiscount * (1 - $item['discount'] / 100);
            } else {
                // Apply amount discount, ensuring it doesn't exceed the total price
                $discountedPrice = max(0, $totalPriceBeforeDiscount - $item['discount']);
            }

            // Add the discounted price to the total amount
            $totalAmount += $discountedPrice;

            // Create StockInItem for each item in the loop
            StockInItem::create([
                'stock_in_id' => $stockIn->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price_per_unit' => $item['price_per_unit'],
                'discount' => $item['discount'] ?? 0,
                'discount_type' => $item['discount_type'],
                'total_price' => $discountedPrice, // Store the total price after discount
                'expiration_date' => $item['expiration_date'] ?? null,
                'manufacturing_date' => $item['manufacturing_date'] ?? null,
            ]);
        }

        // Update the StockIn record with the total amount
        $stockIn->update(['total_amount' => $totalAmount]);

        return redirect()->route('stock-ins.index')->with('success', 'StockIn created successfully!');
    }

    // Show the Edit StockIn form
    public function edit($id)
    {
        $stockIn = StockIn::with('items')->findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('pages.stockins.edit', compact('stockIn', 'suppliers', 'products'));
    }

    // Update StockIn and its items
    public function update(Request $request, $id)
    {
        $stockIn = StockIn::findOrFail($id);

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_in_date' => 'required|date',
            'batch_no' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
            'items.*.price_per_unit' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.discount_type' => 'nullable|in:amount,percentage',
            'items.*.expiration_date' => 'nullable|date',
            'items.*.manufacturing_date' => 'nullable|date'
        ]);

        // Update StockIn
        $stockIn->update([
            'supplier_id' => $validated['supplier_id'],
            'stock_in_date' => $validated['stock_in_date'],
            'batch_no' => $validated['batch_no'],
        ]);

        // Clear old items and add new ones
        $stockIn->items()->delete();

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalPriceBeforeDiscount = $item['quantity'] * $item['price_per_unit'];
            $discountedPrice = $item['discount_type'] === 'percentage'
                ? $totalPriceBeforeDiscount * (1 - $item['discount'] / 100)
                : max(0, $totalPriceBeforeDiscount - $item['discount']);
            $totalAmount += $discountedPrice;

            StockInItem::create([
                'stock_in_id' => $stockIn->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price_per_unit' => $item['price_per_unit'],
                'discount' => $item['discount'] ?? 0,
                'discount_type' => $item['discount_type'],
                'total_price' => $discountedPrice, // Store total price after discount
                'expiration_date' => $item['expiration_date'] ?? null,
                'manufacturing_date' => $item['manufacturing_date'] ?? null
            ]);
        }

        // Update the total amount again
        $stockIn->update(['total_amount' => $totalAmount]);

        return redirect()->route('stock-ins.index')->with('success', 'StockIn updated successfully!');
    }

    public function show($id)
{
    $stockIn = StockIn::with('supplier', 'items.product')->findOrFail($id);
    return view('pages.stockins.show', compact('stockIn'));
}


    // Delete StockIn
    public function destroy($id)
    {
        $stockIn = StockIn::findOrFail($id);
        $stockIn->delete();

        return redirect()->route('stock-ins.index')->with('success', 'StockIn deleted successfully.');
    }



    public function generateInvoice($stockInId)
    {
        $stockIn = StockIn::with('items.product', 'payments', 'supplier')->findOrFail($stockInId);

        // Calculate total payments and balance due
        $totalPaid = $stockIn->payments->sum('amount_paid');
        $balanceDue = $stockIn->total_amount - $totalPaid;

        // Load the view and generate the PDF
        $pdf = DomPDF::loadView('pages.invoices.pdfgen', compact('stockIn', 'totalPaid', 'balanceDue'));

        // Return the generated PDF as a download
        return $pdf->download('invoice_' . $stockIn->id . '.pdf');
    }


}
