<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::all(); // Fetch all suppliers
        return view('pages.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'company_name' => 'required|string|max:255',
            'registerNum' => 'nullable|string|unique:suppliers,registerNum',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'phone_numbers' => 'nullable|array',
            'has_discount' => 'required|boolean',
            'discount_type' => 'nullable|in:percentage,amount',
            'discount_value' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'tax_identification_number' => 'nullable|string',
            'supplier_type' => 'required|in:local,international,manufacturer,distributor',
            'delivery_methods' => 'nullable|in:courier,own_vehicle,drop_shipping,pickup,postal_service',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
        ]);

        try {
            // Create a new supplier with a custom ID
            $supplier = new Supplier();
            $supplier->id = 'sup' . str_pad(Supplier::count() + 1, 3, '0', STR_PAD_LEFT); // Generate ID
            $supplier->fill($request->all());
            $supplier->save();

            return redirect()->back()->with('success', 'Supplier created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', 'An error occurred while creating the supplier: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing an existing supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in the database.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validate the request data
        $request->validate([
            'company_name' => 'required|string|max:255',
            'registerNum' => 'nullable|string|unique:suppliers,registerNum,' . $supplier->id,
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'phone_numbers' => 'nullable|array',
            'has_discount' => 'required|boolean',
            'discount_type' => 'nullable|in:percentage,amount',
            'discount_value' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'tax_identification_number' => 'nullable|string',
            'supplier_type' => 'required|in:local,international,manufacturer,distributor',
            'delivery_methods' => 'nullable|in:courier,own_vehicle,drop_shipping,pickup,postal_service',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
        ]);

        try {
            // Update supplier details, keeping the ID unchanged
            $supplier->update($request->except('id')); // Exclude ID from the update

            return redirect()->back()->with('warning', 'Supplier updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', 'An error occurred while updating the supplier: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified supplier from the database.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // Delete the supplier
            $supplier->delete();

            return redirect()->back()->with('error', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('toast_error', 'An error occurred while deleting the supplier: ' . $e->getMessage());
        }
    }
}
