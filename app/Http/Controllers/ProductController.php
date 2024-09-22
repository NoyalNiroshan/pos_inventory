<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        // Fetch all products with their associated brand and subcategory
        $products = Product::with(['brand', 'subcategory'])->get();
        $brands = Brand::all(); // To populate the brand dropdown in the form
        $subcategories = Subcategory::all(); // To populate the subcategory dropdown in the form

        // Add all stock type options for the dropdown
        $stockTypes = ['liquid', 'solid', 'dress', 'powder', 'gas', 'electronics', 'medicine', 'furniture', 'cosmetics', 'food', 'beverage'];

        return view('pages.products.index', compact('products', 'brands', 'subcategories', 'stockTypes'));
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'stock_type' => 'required|in:liquid,solid,dress,powder,gas,electronics,medicine,furniture,cosmetics,food,beverage', // Validate stock type
            'serial_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'in_stock' => 'boolean',
            'on_sale' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        try {
            // Handle the image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
            }

            // Create the product
            Product::create([
                'brand_id' => $validatedData['brand_id'],
                'subcategory_id' => $validatedData['subcategory_id'],
                'name' => $validatedData['name'],
                'stock_type' => $validatedData['stock_type'],
                'serial_number' => $validatedData['serial_number'] ?? null,
                'is_active' => $request->is_active ?? false,
                'in_stock' => $request->in_stock ?? false,
                'on_sale' => $request->on_sale ?? false,
                'image' => $imagePath,
                'description' => $validatedData['description'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified product in the database.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'stock_type' => 'required|in:liquid,solid,dress,powder,gas,electronics,medicine,furniture,cosmetics,food,beverage', // Validate stock type
            'serial_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'in_stock' => 'boolean',
            'on_sale' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        try {
            // Handle the image upload if available
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }
                $imagePath = $request->file('image')->store('product_images', 'public');
            } else {
                $imagePath = $product->image;
            }

            // Update the product
            $product->update([
                'brand_id' => $validatedData['brand_id'],
                'subcategory_id' => $validatedData['subcategory_id'],
                'name' => $validatedData['name'],
                'stock_type' => $validatedData['stock_type'],
                'serial_number' => $validatedData['serial_number'] ?? null,
                'is_active' => $request->is_active ?? false,
                'in_stock' => $request->in_stock ?? false,
                'on_sale' => $request->on_sale ?? false,
                'image' => $imagePath,
                'description' => $validatedData['description'] ?? null,
            ]);

            return redirect()->back()->with('warning', 'Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from the database.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            // Delete image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $product->delete();

            return redirect()->back()->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
