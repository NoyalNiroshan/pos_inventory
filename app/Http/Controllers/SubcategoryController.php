<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        $categories = Category::all();

        return view('pages.subcategories.index', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategory_images', 'public');
        }

        Subcategory::create([
            'category_id' => $validatedData['category_id'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->back()->with('success', 'Subcategory created successfully');
    }

    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($subcategory->image) {
                Storage::delete('public/' . $subcategory->image);
            }
            $imagePath = $request->file('image')->store('subcategory_images', 'public');
        } else {
            $imagePath = $subcategory->image;
        }

        $subcategory->update([
            'category_id' => $validatedData['category_id'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->back()->with('warning', 'Subcategory updated successfully');
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        if ($subcategory->image) {
            Storage::delete('public/' . $subcategory->image);
        }

        $subcategory->delete();

        return redirect()->back()->with('error', 'Subcategory deleted successfully');
    }
}
