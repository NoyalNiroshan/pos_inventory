<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index()
    {
        // Fetch all brands
        $brands = Brand::all();

        // Return to the view with the brands data
        return view('pages.brands.index', compact('brands'));
    }

    /**
     * Store a newly created or updated brand.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Prepare data for storing or updating the brand
        $brandData = [
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle the image upload if available
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brand_images', 'public');
            $brandData['image'] = $imagePath;
        }

        // Create or update the brand based on whether the request contains an 'id'
        Brand::updateOrCreate(
            ['id' => $request->id],
            $brandData
        );

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Brand saved successfully');
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy($id)
    {
        // Find the brand by ID and delete it
        $brand = Brand::findOrFail($id);
        $brand->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Brand deleted successfully');
    }

    /**
     * Show the form for editing the specified brand (optional).
     */
    public function edit($id)
    {
        // Find the brand by ID
        $brand = Brand::findOrFail($id);

        // Return to the view with the brand data
        return view('pages.brands.edit', compact('brand'));
    }
}
