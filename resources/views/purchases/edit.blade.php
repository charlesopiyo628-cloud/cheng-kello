@extends('layouts.app')

@section('title', 'Edit Purchase')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Purchase</h2>

    <form method="POST" action="{{ route('purchases.update', $purchase) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="fish_category_id" class="block text-gray-700 text-sm font-bold mb-2">Fish Category *</label>
                <select id="fish_category_id" name="fish_category_id" required onchange="updateCostPrice()"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" data-purchase-cost="{{ $category->purchase_cost }}"
                                {{ $purchase->fish_category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} - {{ $category->description }} (Cost: UGX {{ number_format($category->purchase_cost, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity *</label>
                <input type="number" id="quantity" name="quantity" min="1" required onchange="calculateTotal()"
                       value="{{ $purchase->quantity }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="cost_price" class="block text-gray-700 text-sm font-bold mb-2">Cost Price (UGX) *</label>
                <input type="number" id="cost_price" name="cost_price" min="0" step="0.01" required onchange="calculateTotal()"
                       value="{{ $purchase->cost_price }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-xs text-gray-500 mt-1">Auto-filled from fish category purchase cost</p>
            </div>

            <div>
                <label for="total_cost" class="block text-gray-700 text-sm font-bold mb-2">Total Cost (UGX)</label>
                <input type="text" id="total_cost" name="total_cost" readonly
                       value="{{ number_format($purchase->total_cost, 2) }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="purchase_date" class="block text-gray-700 text-sm font-bold mb-2">Purchase Date *</label>
                <input type="date" id="purchase_date" name="purchase_date" required
                       value="{{ $purchase->purchase_date->format('Y-m-d') }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="supplier_name" class="block text-gray-700 text-sm font-bold mb-2">Supplier Name</label>
                <input type="text" id="supplier_name" name="supplier_name"
                       value="{{ $purchase->supplier_name }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="mb-4">
            <label for="invoice_number" class="block text-gray-700 text-sm font-bold mb-2">Invoice Number</label>
            <input type="text" id="invoice_number" name="invoice_number"
                   value="{{ $purchase->invoice_number }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
            <textarea id="notes" name="notes" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $purchase->notes }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Purchase
            </button>
            <a href="{{ route('purchases.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function updateCostPrice() {
    const categorySelect = document.getElementById('fish_category_id');
    const costPriceInput = document.getElementById('cost_price');

    if (categorySelect.value) {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const purchaseCost = selectedOption.getAttribute('data-purchase-cost');
        if (purchaseCost) {
            costPriceInput.value = purchaseCost;
            calculateTotal();
        }
    }
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    const totalCost = quantity * costPrice;

    document.getElementById('total_cost').value = totalCost.toFixed(2);
}

// Initialize total cost on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection