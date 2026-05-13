@extends('layouts.app')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Today's Sales</h3>
        <p class="text-3xl font-bold text-green-600">UGX {{ number_format($stats['my_sales_today'], 2) }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Sales</h3>
        <p class="text-3xl font-bold text-green-600">UGX {{ number_format($stats['my_sales'], 2) }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Quantity Sold</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['my_sales_quantity'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Available Stock</h3>
        <p class="text-3xl font-bold {{ $stats['remaining_stock'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $stats['remaining_stock'] }}</p>
        @if($stats['remaining_stock'] <= 0)
            <p class="text-xs text-red-500 mt-1">Out of Stock</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('sales.create') }}" class="block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                Record New Sale
            </a>
            <a href="{{ route('stocks.create') }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                Add Stock
            </a>
            <a href="{{ route('sales.index') }}" class="block bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                View My Sales
            </a>
            <a href="{{ route('stocks.index') }}" class="block bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                View Stocks
            </a>
            <a href="{{ route('purchases.index') }}" class="block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded text-center">
                View Purchases
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">My Recent Sales</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mySales as $sale)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $sale->sale_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $sale->fishCategory->name }}</td>
                            <td class="px-4 py-2">{{ $sale->quantity }}</td>
                            <td class="px-4 py-2">UGX {{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">My Recent Stock Additions</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myStocks as $stock)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $stock->stock_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $stock->fishCategory->name }}</td>
                        <td class="px-4 py-2">{{ $stock->quantity }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($stock->status == 'approved') bg-green-100 text-green-800
                                @elseif($stock->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($stock->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
