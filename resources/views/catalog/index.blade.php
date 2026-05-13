@extends('layouts.app')

@section('title', 'Product Catalog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Product Catalog</h2>
            <p class="text-gray-600 mt-2">Browse available fish categories and pricing per kilo.</p>
        </div>
    </div>

    <div class="grid gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $category)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                @if($category->image)
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-48 w-full object-cover">
                @else
                    <div class="h-48 w-full bg-gray-100 flex items-center justify-center text-gray-500">
                        No image available
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $category->name }}</h3>
                    <p class="text-gray-600 mt-1">{{ $category->description ?? 'No description available.' }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-bold text-blue-600">UGX {{ number_format($category->unit_price, 2) }} / kg</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold uppercase tracking-wide {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <p class="text-gray-700">No product categories are available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
