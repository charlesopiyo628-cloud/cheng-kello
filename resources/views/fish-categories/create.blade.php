@extends('layouts.app')

@section('title', 'Add Fish Category')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add Fish Category</h2>
    
    <form method="POST" action="{{ route('fish-categories.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Category Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="10"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-xs text-gray-500 mt-1">e.g., M, L, XL, SXL (max 10 characters)</p>
        </div>
        
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <input type="text" id="description" name="description" value="{{ old('description') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-xs text-gray-500 mt-1">e.g., Medium, Large, Extra Large</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="purchase_cost" class="block text-gray-700 text-sm font-bold mb-2">Purchase Cost (UGX) *</label>
                <input type="number" id="purchase_cost" name="purchase_cost" value="{{ old('purchase_cost') }}" required min="0" step="0.01"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-xs text-gray-500 mt-1">Cost per kg for purchases</p>
            </div>
            
            <div>
                <label for="unit_price" class="block text-gray-700 text-sm font-bold mb-2">Selling Price (UGX) *</label>
                <input type="number" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required min="0" step="0.01"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-xs text-gray-500 mt-1">Price per kg for sales</p>
            </div>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Category Image</label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="block w-full text-sm text-gray-700 border border-gray-300 rounded py-2 px-3 focus:outline-none focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Upload an image for this category (optional).</p>
        </div>
        
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                       class="mr-2 leading-tight">
                <span class="text-sm">Active</span>
            </label>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Category
            </button>
            <a href="{{ route('fish-categories.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
