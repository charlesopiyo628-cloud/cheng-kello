<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FishCategory;

class FishCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->except(['browse']);
        $this->middleware('auth')->only(['browse']);
    }

    public function index()
    {
        $categories = FishCategory::orderBy('name')->get();
        return view('fish-categories.index', compact('categories'));
    }

    public function browse()
    {
        $categories = FishCategory::where('is_active', true)->orderBy('name')->get();
        return view('catalog.index', compact('categories'));
    }

    public function create()
    {
        return view('fish-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:10|unique:fish_categories',
            'description' => 'nullable|string|max:255',
            'purchase_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/categories'), $filename);
            $imagePath = 'uploads/categories/' . $filename;
        }

        FishCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'purchase_cost' => $request->purchase_cost,
            'unit_price' => $request->unit_price,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('fish-categories.index')
            ->with('success', 'Fish category created successfully.');
    }

    public function edit(FishCategory $fishCategory)
    {
        return view('fish-categories.edit', compact('fishCategory'));
    }

    public function update(Request $request, FishCategory $fishCategory)
    {
        $request->validate([
            'name' => 'required|string|max:10|unique:fish_categories,name,' . $fishCategory->id,
            'description' => 'nullable|string|max:255',
            'purchase_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'purchase_cost' => $request->purchase_cost,
            'unit_price' => $request->unit_price,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/categories'), $filename);
            $updateData['image'] = 'uploads/categories/' . $filename;
        }

        $fishCategory->update($updateData);

        return redirect()->route('fish-categories.index')
            ->with('success', 'Fish category updated successfully.');
    }

    public function destroy(FishCategory $fishCategory)
    {
        // Check if category has related records
        if ($fishCategory->stocks()->count() > 0 || 
            $fishCategory->sales()->count() > 0 || 
            $fishCategory->purchases()->count() > 0) {
            return back()->withErrors(['delete' => 'Cannot delete fish category with existing records.']);
        }

        $fishCategory->delete();

        return redirect()->route('fish-categories.index')
            ->with('success', 'Fish category deleted successfully.');
    }
}
