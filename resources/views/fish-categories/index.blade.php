@extends('layouts.app')

@section('title', 'Fish Categories')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Fish Categories</h2>
        <a href="{{ route('fish-categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Category
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Image</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Purchase Cost (UGX)</th>
                    <th class="px-4 py-2 text-left">Price per Kilo (UGX)</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-b">
                        <td class="px-4 py-2 font-semibold">{{ $category->name }}</td>
                        <td class="px-4 py-2">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-16 w-16 object-cover rounded">
                            @else
                                <span class="text-sm text-gray-500">No image</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $category->description ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ number_format($category->purchase_cost, 2) }}</td>
                        <td class="px-4 py-2">{{ number_format($category->unit_price, 2) }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($category->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('fish-categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                            <form action="{{ route('fish-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this fish category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
