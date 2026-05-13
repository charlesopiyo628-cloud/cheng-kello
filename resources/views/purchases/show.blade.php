@extends('layouts.app')

@section('title', 'Purchase Details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Purchase Details</h2>
        <div class="space-x-2">
            <a href="{{ route('purchases.edit', $purchase) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Edit Purchase
            </a>
            <a href="{{ route('purchases.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Purchases
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Purchase Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">Purchase Date:</span>
                    <span>{{ $purchase->purchase_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Fish Category:</span>
                    <span>{{ $purchase->fishCategory->name }} - {{ $purchase->fishCategory->description }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Quantity:</span>
                    <span>{{ $purchase->quantity }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Cost Price:</span>
                    <span>UGX {{ number_format($purchase->cost_price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Total Cost:</span>
                    <span class="text-lg font-bold text-purple-600">UGX {{ number_format($purchase->total_cost, 2) }}</span>
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Supplier Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">Supplier Name:</span>
                    <span>{{ $purchase->supplier_name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Invoice Number:</span>
                    <span>{{ $purchase->invoice_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Created By:</span>
                    <span>{{ $purchase->createdBy->name }} ({{ $purchase->createdBy->role }})</span>
                </div>
            </div>
        </div>
    </div>
    
    @if($purchase->notes)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
            <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $purchase->notes }}</p>
        </div>
    @endif
</div>
@endsection
