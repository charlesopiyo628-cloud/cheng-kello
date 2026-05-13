@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Sale Details</h2>
        <a href="{{ route('sales.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Sales
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sale Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">Sale Date:</span>
                    <span>{{ $sale->sale_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Fish Category:</span>
                    <span>{{ $sale->fishCategory->name }} - {{ $sale->fishCategory->description }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Quantity:</span>
                    <span>{{ $sale->quantity }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Unit Price:</span>
                    <span>UGX {{ number_format($sale->unit_price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Total Amount:</span>
                    <span class="text-lg font-bold text-green-600">UGX {{ number_format($sale->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">Customer Name:</span>
                    <span>{{ $sale->customer_name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Customer Phone:</span>
                    <span>{{ $sale->customer_phone ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Sold By:</span>
                    <span>{{ $sale->soldBy->name }} ({{ $sale->soldBy->role }})</span>
                </div>
            </div>
        </div>
    </div>
    
    @if($sale->notes)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
            <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $sale->notes }}</p>
        </div>
    @endif
</div>
@endsection
