@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Sales</h2>
        @if(auth()->user()->isCashier() || auth()->user()->isAdmin())
            <a href="{{ route('sales.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Add New Sale
            </a>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Unit Price</th>
                    <th class="px-4 py-2 text-left">Total Amount</th>
                    <th class="px-4 py-2 text-left">Customer</th>
                    <th class="px-4 py-2 text-left">Sold By</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $sale->sale_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $sale->fishCategory->name }}</td>
                        <td class="px-4 py-2">{{ $sale->quantity }}</td>
                        <td class="px-4 py-2">UGX {{ number_format($sale->unit_price, 2) }}</td>
                        <td class="px-4 py-2">UGX {{ number_format($sale->total_amount, 2) }}</td>
                        <td class="px-4 py-2">{{ $sale->customer_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $sale->soldBy->name }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this sale record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
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
