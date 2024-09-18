<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('pages.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brand_images', 'public');
        }

        // Create a new brand
        Brand::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->back()->with('success', 'Brand created successfully');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle the image upload if available
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($brand->image) {
                Storage::delete('public/' . $brand->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('brand_images', 'public');
        } else {
            $imagePath = $brand->image;
        }

        // Update the brand with new data
        $brand->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->back()->with('success', 'Brand updated successfully');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image) {
            Storage::delete('public/' . $brand->image);
        }

        $brand->delete();

        return redirect()->back()->with('success', 'Brand deleted successfully');
    }
}
