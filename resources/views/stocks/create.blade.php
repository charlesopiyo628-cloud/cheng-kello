@extends('layouts.app')

@section('title', 'Add Stock')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Stock</h2>
    
    <form method="POST" action="{{ route('stocks.store') }}">
        @csrf
        
        <div class="mb-4">
            <label class="flex items-center mb-4">
                <input type="radio" name="stock_source" value="manual" checked onchange="toggleStockSource(this.value)" class="mr-2">
                <span class="text-sm font-medium">Manual Entry</span>
            </label>
            <label class="flex items-center mb-4">
                <input type="radio" name="stock_source" value="purchase" onchange="toggleStockSource(this.value)" class="mr-2">
                <span class="text-sm font-medium">From Purchase</span>
            </label>
        </div>

        <!-- Manual Entry Section -->
        <div id="manual-section" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="fish_category_id" class="block text-gray-700 text-sm font-bold mb-2">Fish Category *</label>
                <select id="fish_category_id" name="fish_category_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }} - {{ $category->description }} (Sell: UGX {{ number_format($category->unit_price, 2) }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity *</label>
                <input type="number" id="quantity" name="quantity" min="1" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <!-- Purchase Selection Section -->
        <div id="purchase-section" class="hidden">
            <div class="mb-4">
                <label for="purchase_id" class="block text-gray-700 text-sm font-bold mb-2">Select Purchase (Optional)</label>
                <select id="purchase_id" name="purchase_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">-- Select Purchase --</option>
                    @foreach($purchases as $purchase)
                        <option value="{{ $purchase->id }}" 
                                data-category="{{ $purchase->fish_category_id }}"
                                data-quantity="{{ $purchase->quantity }}"
                                data-cost="{{ $purchase->cost_price }}"
                                data-date="{{ $purchase->purchase_date->format('Y-m-d') }}"
                                @if($mostRecentPurchase && $mostRecentPurchase->id == $purchase->id) selected @endif>
                            {{ $purchase->purchase_date->format('d/m/Y') }} - {{ $purchase->fishCategory->name }} - {{ $purchase->quantity }} units - UGX {{ number_format($purchase->total_cost, 2) }}
                        </option>
                    @endforeach
                </select>
                @if($mostRecentPurchase)
                    <p class="text-xs text-green-600 mt-1">📌 Most recent purchase auto-selected</p>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="cost_price" class="block text-gray-700 text-sm font-bold mb-2">Cost Price (UGX)</label>
                <input type="number" id="cost_price" name="cost_price" min="0" step="0.01"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-xs text-gray-500 mt-1">Optional - for purchase tracking</p>
            </div>
            
            <div>
                <label for="stock_date" class="block text-gray-700 text-sm font-bold mb-2">Stock Date *</label>
                <input type="date" id="stock_date" name="stock_date" value="{{ date('Y-m-d') }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>
        
        <div class="mb-6">
            <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
            <textarea id="notes" name="notes" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6">
            <p class="text-sm text-yellow-800">
                <strong>Note:</strong> This stock will require admin approval before it becomes available for sales.
            </p>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Stock
            </button>
            <a href="{{ route('stocks.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </div>
</div>
</form>
</div>

<script>
// Auto-fill on page load if most recent purchase is selected
document.addEventListener('DOMContentLoaded', function() {
    const purchaseSelect = document.getElementById('purchase_id');
    if (purchaseSelect && purchaseSelect.value) {
        fillFromPurchase(purchaseSelect.value);
    }
});

function toggleStockSource(value) {
    const manualSection = document.getElementById('manual-section');
    const purchaseSection = document.getElementById('purchase-section');
    
    if (value === 'manual') {
        manualSection.classList.remove('hidden');
        purchaseSection.classList.add('hidden');
        // Clear purchase selection and make manual fields required
        document.getElementById('purchase_id').value = '';
        document.getElementById('fish_category_id').required = true;
        document.getElementById('quantity').required = true;
        document.getElementById('purchase_id').required = false;
    } else {
        manualSection.classList.add('hidden');
        purchaseSection.classList.remove('hidden');
        // Make purchase required and manual fields not required
        document.getElementById('purchase_id').required = true;
        document.getElementById('fish_category_id').required = false;
        document.getElementById('quantity').required = false;
        // Auto-fill if purchase is selected
        const purchaseSelect = document.getElementById('purchase_id');
        if (purchaseSelect.value) {
            fillFromPurchase(purchaseSelect.value);
        }
    }
}

function fillFromPurchase(purchaseId) {
    if (!purchaseId) {
        // Clear manual fields
        document.getElementById('fish_category_id').value = '';
        document.getElementById('quantity').value = '';
        document.getElementById('cost_price').value = '';
        document.getElementById('stock_date').value = '';
        return;
    }
    
    const select = document.getElementById('purchase_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        // Fill manual fields with purchase data
        document.getElementById('fish_category_id').value = selectedOption.getAttribute('data-category');
        document.getElementById('quantity').value = selectedOption.getAttribute('data-quantity');
        document.getElementById('cost_price').value = selectedOption.getAttribute('data-cost');
        document.getElementById('stock_date').value = selectedOption.getAttribute('data-date');
    }
}

// Add change event listener to purchase select
document.getElementById('purchase_id')?.addEventListener('change', function() {
    fillFromPurchase(this.value);
});
</script>
@endsection
