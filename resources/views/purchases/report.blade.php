@extends('layouts.app')

@section('title', 'Purchase Report')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Purchase Report</h2>
        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Dashboard
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-purple-800 mb-2">Total Purchases</h3>
            <p class="text-2xl font-bold text-purple-600">UGX {{ number_format($totalPurchases, 2) }}</p>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Quantity</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $totalQuantity }}</p>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-green-800 mb-2">Average Cost</h3>
            <p class="text-2xl font-bold text-green-600">UGX {{ number_format($totalQuantity > 0 ? $totalPurchases / $totalQuantity : 0, 2) }}</p>
        </div>
    </div>
    
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Purchases by Category</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Unit Price</th>
                        <th class="px-4 py-2 text-left">Quantity Purchased</th>
                        <th class="px-4 py-2 text-left">Total Cost</th>
                        <th class="px-4 py-2 text-left">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasesByCategory as $categoryId => $data)
                        <tr class="border-b">
                            <td class="px-4 py-2 font-semibold">{{ $data['category']->name }}</td>
                            <td class="px-4 py-2">{{ $data['category']->description }}</td>
                            <td class="px-4 py-2">UGX {{ number_format($data['category']->unit_price, 2) }}</td>
                            <td class="px-4 py-2">{{ $data['quantity'] }}</td>
                            <td class="px-4 py-2 font-semibold">UGX {{ number_format($data['total'], 2) }}</td>
                            <td class="px-4 py-2">
                                {{ $totalPurchases > 0 ? round(($data['total'] / $totalPurchases) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">All Purchases</h3>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
