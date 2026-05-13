@extends('layouts.app')

@section('title', 'Purchases')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Purchase Management</h2>
        @if(auth()->user()->isAdmin())
            <div class="space-x-2">
                <a href="{{ route('purchase.create.alt') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Purchase
                </a>
            </div>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Cost Price</th>
                    <th class="px-4 py-2 text-left">Total Cost</th>
                    <th class="px-4 py-2 text-left">Supplier</th>
                    <th class="px-4 py-2 text-left">Invoice</th>
                    <th class="px-4 py-2 text-left">Created By</th>
                    @if(auth()->user()->role === 'admin')
                        <th class="px-4 py-2 text-left">Actions</th>
                    @else
                        <th class="px-4 py-2 text-left">Quick Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $purchase->fishCategory->name }}</td>
                        <td class="px-4 py-2">{{ $purchase->quantity }}</td>
                        <td class="px-4 py-2">UGX {{ number_format($purchase->cost_price, 2) }}</td>
                        <td class="px-4 py-2 font-semibold">UGX {{ number_format($purchase->total_cost, 2) }}</td>
                        <td class="px-4 py-2">{{ $purchase->supplier_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $purchase->invoice_number ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $purchase->createdBy->name }}</td>
                        @if(auth()->user()->role === 'admin')
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('purchases.edit', $purchase) }}" class="text-green-600 hover:text-green-800">Edit</a>
                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this purchase record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        @else
                            <td class="px-4 py-2">
                                <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-800 font-semibold">View</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
