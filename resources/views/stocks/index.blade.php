@extends('layouts.app')

@section('title', 'Stocks')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Stock Management</h2>
        <a href="{{ route('stocks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Stock
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity Added</th>
                    <th class="px-4 py-2 text-left">Remaining Stock</th>
                    <th class="px-4 py-2 text-left">Cost Price</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Added By</th>
                    <th class="px-4 py-2 text-left">Approved By</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $stock->stock_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $stock->fishCategory->name }}</td>
                        <td class="px-4 py-2 font-semibold">{{ $stock->quantity }}</td>
                        <td class="px-4 py-2">
                            <span class="@if($remainingStocks[$stock->fish_category_id] <= 0) text-red-600 font-semibold @else text-green-600 @endif">
                                {{ $remainingStocks[$stock->fish_category_id] ?? 0 }}
                            </span>
                            @if($remainingStocks[$stock->fish_category_id] <= 0)
                                <span class="text-xs text-red-500 block">Out of Stock</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            {{ $stock->cost_price ? 'UGX ' . number_format($stock->cost_price, 2) : 'N/A' }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($stock->status == 'approved') bg-green-100 text-green-800
                                @elseif($stock->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($stock->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $stock->addedBy->name }}</td>
                        <td class="px-4 py-2">{{ $stock->approvedBy ? $stock->approvedBy->name : 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('stocks.show', $stock) }}" class="text-blue-600 hover:text-blue-800 mr-2">View</a>
                            @if((auth()->user()->isAdmin() || ($stock->added_by == auth()->id() && $stock->status == 'pending')))
                                <a href="{{ route('stocks.edit', $stock) }}" class="text-green-600 hover:text-green-800 mr-2">Edit</a>
                            @endif
                            @if(auth()->user()->isAdmin() && $stock->status == 'pending')
                                <form method="POST" action="{{ route('stocks.approve', $stock) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs mr-1">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('stocks.reject', $stock) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs mr-1">Reject</button>
                                </form>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('stocks.destroy', $stock) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this stock record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
