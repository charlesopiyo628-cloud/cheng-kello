@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Users</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Pending Stocks</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_stocks'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Stock</h3>
        <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_stock'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Remaining Stock</h3>
        <p class="text-3xl font-bold {{ $stats['remaining_stock'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $stats['remaining_stock'] }}</p>
        @if($stats['remaining_stock'] <= 0)
            <p class="text-xs text-red-500 mt-1">Out of Stock</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Today's Sales</h3>
        <p class="text-3xl font-bold text-green-600">UGX {{ number_format($stats['total_sales_today'], 2) }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Today's Purchases</h3>
        <p class="text-3xl font-bold text-purple-600">UGX {{ number_format($stats['total_purchases_today'], 2) }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Sold</h3>
        <p class="text-3xl font-bold text-orange-600">{{ $stats['total_sold'] }}</p>
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Sales</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-left">Sold By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $sale->sale_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $sale->fishCategory->name }}</td>
                            <td class="px-4 py-2">{{ $sale->quantity }}</td>
                            <td class="px-4 py-2">UGX {{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $sale->soldBy->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pending Stock Approvals</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Quantity</th>
                        <th class="px-4 py-2 text-left">Added By</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingStocks as $stock)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $stock->stock_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $stock->fishCategory->name }}</td>
                            <td class="px-4 py-2">{{ $stock->quantity }}</td>
                            <td class="px-4 py-2">{{ $stock->addedBy->name }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('stocks.approve', $stock) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('stocks.reject', $stock) }}" class="inline ml-1">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
