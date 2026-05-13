@extends('layouts.app')

@section('title', 'Add New Sale')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Sale</h2>
    
    <form method="POST" action="{{ route('sales.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="fish_category_id" class="block text-gray-700 text-sm font-bold mb-2">Fish Category *</label>
                <select id="fish_category_id" name="fish_category_id" required onchange="updatePrice()"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" data-price="{{ $category->unit_price }}">{{ $category->name }} - UGX {{ number_format($category->unit_price, 2) }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity *</label>
                <input type="number" id="quantity" name="quantity" min="1" required onchange="calculateTotal()"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="unit_price" class="block text-gray-700 text-sm font-bold mb-2">Unit Price</label>
                <input type="text" id="unit_price" name="unit_price" readonly
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div>
                <label for="total_amount" class="block text-gray-700 text-sm font-bold mb-2">Total Amount</label>
                <input type="text" id="total_amount" name="total_amount" readonly
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="sale_date" class="block text-gray-700 text-sm font-bold mb-2">Sale Date *</label>
                <input type="date" id="sale_date" name="sale_date" value="{{ date('Y-m-d') }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div>
                <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>
        
        <div class="mb-4">
            <label for="customer_phone" class="block text-gray-700 text-sm font-bold mb-2">Customer Phone</label>
            <input type="text" id="customer_phone" name="customer_phone"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-6">
            <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
            <textarea id="notes" name="notes" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Record Sale
            </button>
            <a href="{{ route('sales.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function updatePrice() {
    const select = document.getElementById('fish_category_id');
    const priceInput = document.getElementById('unit_price');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        priceInput.value = 'UGX ' + parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        calculateTotal();
    } else {
        priceInput.value = '';
        document.getElementById('total_amount').value = '';
    }
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const select = document.getElementById('fish_category_id');
    const selectedOption = select.options[select.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    
    const total = quantity * price;
    const totalInput = document.getElementById('total_amount');
    totalInput.value = total > 0 ? 'UGX ' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '';
}
</script>
@endsection
