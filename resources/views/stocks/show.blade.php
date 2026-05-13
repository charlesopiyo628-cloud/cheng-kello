@extends('layouts.app')

@section('title', 'Stock Details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Stock Details</h2>
        <a href="{{ route('stocks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Stocks
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Stock Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">Stock Date:</span>
                    <span>{{ $stock->stock_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Fish Category:</span>
                    <span>{{ $stock->fishCategory->name }} - {{ $stock->fishCategory->description }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Quantity:</span>
                    <span>{{ $stock->quantity }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Cost Price:</span>
                    <span>{{ $stock->cost_price ? 'UGX ' . number_format($stock->cost_price, 2) : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium">Status:</span>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($stock->status == 'approved') bg-green-100 text-green-800
                        @elseif($stock->status == 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($stock->status) }}
                    </span>
                </div>
                @if($stock->approved_at)
                    <div class="flex justify-between">
                        <span class="font-medium">Approved At:</span>
                        <span>{{ $stock->approved_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">User Information</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">Added By:</span>
                    <span>{{ $stock->addedBy->name }} ({{ $stock->addedBy->role }})</span>
                </div>
                @if($stock->approvedBy)
                    <div class="flex justify-between">
                        <span class="font-medium">Approved By:</span>
                        <span>{{ $stock->approvedBy->name }} ({{ $stock->approvedBy->role }})</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @if($stock->notes)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
            <p class="text-gray-600 bg-gray-50 p-3 rounded">{{ $stock->notes }}</p>
        </div>
    @endif
    
    @if(auth()->user()->isAdmin() && $stock->status == 'pending')
        <div class="mt-6 flex space-x-4">
            <form method="POST" action="{{ route('stocks.approve', $stock) }}">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Approve Stock
                </button>
            </form>
            <form method="POST" action="{{ route('stocks.reject', $stock) }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Reject Stock
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
