@extends('layouts.app')

@section('title', 'Director Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-green-800 mb-2">Today's Sales</h3>
        <p class="text-2xl font-bold text-green-600">UGX {{ number_format($stats['total_sales_today'], 2) }}</p>
    </div>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Sales</h3>
        <p class="text-2xl font-bold text-blue-600">UGX {{ number_format($stats['total_sales'], 2) }}</p>
    </div>
    
    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-indigo-800 mb-2">Total Stock</h3>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total_stock'] }}</p>
    </div>
    
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-orange-800 mb-2">Remaining Stock</h3>
        <p class="text-2xl font-bold {{ $stats['remaining_stock'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $stats['remaining_stock'] }}</p>
        @if($stats['remaining_stock'] <= 0)
            <p class="text-xs text-red-500 mt-1">Out of Stock</p>
        @endif
    </div>
</div>

@if(count($stats['low_stock_categories']) > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
        <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ Low Stock Alert</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stats['low_stock_categories'] as $lowStock)
                <div class="bg-white rounded p-3 border border-red-300">
                    <span class="font-medium">{{ $lowStock['category'] }}</span>
                    <span class="float-right text-red-600 font-bold">{{ $lowStock['remaining'] }} left</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-purple-800 mb-2">Total Quantity Sold</h3>
        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_sales_quantity'] }}</p>
    </div>
    
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-orange-800 mb-2">Total Sold</h3>
        <p class="text-2xl font-bold text-orange-600">{{ $stats['total_sold'] }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <a href="{{ route('purchases.index') }}" class="block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded text-center">
            View Purchases
        </a>
        <a href="{{ route('sales.index') }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
            View Sales
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Sales</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Unit Price</th>
                    <th class="px-4 py-2 text-left">Total Amount</th>
                    <th class="px-4 py-2 text-left">Sold By</th>
                    <th class="px-4 py-2 text-left">Customer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSales as $sale)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $sale->sale_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $sale->fishCategory->name }}</td>
                        <td class="px-4 py-2">{{ $sale->quantity }}</td>
                        <td class="px-4 py-2">UGX {{ number_format($sale->unit_price, 2) }}</td>
                        <td class="px-4 py-2">UGX {{ number_format($sale->total_amount, 2) }}</td>
                        <td class="px-4 py-2">{{ $sale->soldBy->name }}</td>
                        <td class="px-4 py-2">{{ $sale->customer_name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
