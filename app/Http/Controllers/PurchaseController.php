<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\FishCategory;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // Removed role:admin middleware from constructor to allow different access levels

    public function index()
    {
        $purchases = Purchase::with(['fishCategory', 'createdBy'])
                          ->orderBy('purchase_date', 'desc')
                          ->get();
        
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $categories = FishCategory::where('is_active', true)->get();
        $invoiceNumber = $this->generateInvoiceNumber();
        return view('purchases.create', compact('categories', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fish_category_id' => 'required|exists:fish_categories,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'supplier_name' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $totalCost = $request->quantity * $request->cost_price;

        // Generate invoice number if not provided
        $invoiceNumber = $request->invoice_number ?: $this->generateInvoiceNumber();

        Purchase::create([
            'fish_category_id' => $request->fish_category_id,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'total_cost' => $totalCost,
            'created_by' => Auth::id(),
            'purchase_date' => $request->purchase_date,
            'supplier_name' => $request->supplier_name,
            'invoice_number' => $invoiceNumber,
            'notes' => $request->notes,
        ]);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase recorded successfully.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['fishCategory', 'createdBy']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $categories = FishCategory::where('is_active', true)->get();
        $purchase->load(['fishCategory', 'createdBy']);
        return view('purchases.edit', compact('purchase', 'categories'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'fish_category_id' => 'required|exists:fish_categories,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'supplier_name' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $totalCost = $request->quantity * $request->cost_price;

        $purchase->update([
            'fish_category_id' => $request->fish_category_id,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'total_cost' => $totalCost,
            'purchase_date' => $request->purchase_date,
            'supplier_name' => $request->supplier_name,
            'invoice_number' => $request->invoice_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase updated successfully.');
    }

    public function report()
    {
        $purchases = Purchase::with(['fishCategory', 'createdBy'])
                            ->orderBy('purchase_date', 'desc')
                            ->get();

        $totalPurchases = $purchases->sum('total_cost');
        $totalQuantity = $purchases->sum('quantity');
        
        $purchasesByCategory = $purchases->groupBy('fish_category_id')
                                  ->map(function ($categoryPurchases) {
                                      return [
                                          'quantity' => $categoryPurchases->sum('quantity'),
                                          'total' => $categoryPurchases->sum('total_cost'),
                                          'category' => $categoryPurchases->first()->fishCategory
                                      ];
                                  });

        return view('purchases.report', compact('purchases', 'totalPurchases', 'totalQuantity', 'purchasesByCategory'));
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase deleted successfully.');
    }

    /**
     * Generate a unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $prefix = 'INV-' . $year . '-';

        // Get the last invoice number for this year
        $lastPurchase = Purchase::where('invoice_number', 'like', $prefix . '%')
                               ->orderBy('invoice_number', 'desc')
                               ->first();

        if ($lastPurchase) {
            // Extract the sequential number and increment
            $lastNumber = intval(substr($lastPurchase->invoice_number, strlen($prefix)));
            $newNumber = $lastNumber + 1;
        } else {
            // Start with 1 if no invoices exist for this year
            $newNumber = 1;
        }

        // Format as 4-digit number (e.g., 0001, 0002, etc.)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
