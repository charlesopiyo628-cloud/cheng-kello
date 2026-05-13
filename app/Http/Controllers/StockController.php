<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\FishCategory;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $stocks = Stock::with(['fishCategory', 'addedBy', 'approvedBy'])->get();
        } elseif ($user->isDirector()) {
            $stocks = Stock::with(['fishCategory', 'addedBy', 'approvedBy'])->get();
        } else {
            $stocks = Stock::with(['fishCategory', 'addedBy', 'approvedBy'])
                        ->where('added_by', $user->id)
                        ->orWhere('status', 'approved')
                        ->get();
        }
        
        // Calculate remaining stock for each category
        $remainingStocks = [];
        foreach ($stocks as $stock) {
            $categoryId = $stock->fish_category_id;
            if (!isset($remainingStocks[$categoryId])) {
                $totalApproved = Stock::where('fish_category_id', $categoryId)
                                   ->where('status', 'approved')
                                   ->sum('quantity');
                $totalSold = Sale::where('fish_category_id', $categoryId)
                                 ->sum('quantity');
                $remainingStocks[$categoryId] = max(0, $totalApproved - $totalSold);
            }
        }
        
        return view('stocks.index', compact('stocks', 'remainingStocks'));
    }

    public function create()
    {
        $categories = FishCategory::where('is_active', true)->get();
        $purchases = \App\Models\Purchase::with('fishCategory')
                                   ->orderBy('purchase_date', 'desc')
                                   ->get();
        
        // Auto-select most recent purchase
        $mostRecentPurchase = $purchases->first();
        
        return view('stocks.create', compact('categories', 'purchases', 'mostRecentPurchase'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fish_category_id' => 'required|exists:fish_categories,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'purchase_id' => 'nullable|exists:purchases,id',
        ]);

        // If purchase is selected, get purchase details
        $purchaseId = $request->purchase_id;
        if ($purchaseId) {
            $purchase = \App\Models\Purchase::find($purchaseId);
            if ($purchase) {
                // Use purchase data
                $stockData = [
                    'fish_category_id' => $purchase->fish_category_id,
                    'quantity' => $purchase->quantity,
                    'cost_price' => $purchase->cost_price,
                    'stock_date' => $purchase->purchase_date,
                    'status' => 'pending',
                    'added_by' => Auth::id(),
                    'notes' => $request->notes ?? 'From Purchase: ' . $purchase->supplier_name,
                ];
            }
        } else {
            // Use manual entry data
            $stockData = [
                'fish_category_id' => $request->fish_category_id,
                'quantity' => $request->quantity,
                'cost_price' => $request->cost_price,
                'stock_date' => $request->stock_date,
                'status' => 'pending',
                'added_by' => Auth::id(),
                'notes' => $request->notes,
            ];
        }

        Stock::create($stockData);

        return redirect()->route('stocks.index')
            ->with('success', 'Stock added successfully and pending approval.');
    }

    public function approve(Stock $stock)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($stock->status !== 'pending') {
            return back()->with('error', 'Stock can only be approved if pending.');
        }

        $stock->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Stock approved successfully.');
    }

    public function reject(Stock $stock)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($stock->status !== 'pending') {
            return back()->with('error', 'Stock can only be rejected if pending.');
        }

        $stock->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Stock rejected successfully.');
    }

    public function show(Stock $stock)
    {
        $stock->load(['fishCategory', 'addedBy', 'approvedBy']);
        return view('stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        // Only allow editing of pending stocks or if user is admin
        if (!Auth::user()->isAdmin() && $stock->status !== 'pending') {
            abort(403, 'You can only edit pending stocks.');
        }
        
        $stock->load(['fishCategory', 'addedBy', 'approvedBy']);
        $categories = FishCategory::where('is_active', true)->get();
        $purchases = \App\Models\Purchase::with('fishCategory')
                                   ->orderBy('purchase_date', 'desc')
                                   ->get();
        
        return view('stocks.edit', compact('stock', 'categories', 'purchases'));
    }

    public function update(Request $request, Stock $stock)
    {
        // Only allow updating pending stocks or if user is admin
        if (!Auth::user()->isAdmin() && $stock->status !== 'pending') {
            abort(403, 'You can only update pending stocks.');
        }
        
        $request->validate([
            'fish_category_id' => 'required|exists:fish_categories,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'purchase_id' => 'nullable|exists:purchases,id',
        ]);

        $stockData = [
            'fish_category_id' => $request->fish_category_id,
            'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'stock_date' => $request->stock_date,
            'notes' => $request->notes,
        ];

        // If purchase is selected, update purchase reference
        if ($request->purchase_id) {
            $purchase = \App\Models\Purchase::find($request->purchase_id);
            if ($purchase) {
                $stockData['notes'] = $request->notes ?? 'From Purchase: ' . $purchase->supplier_name;
            }
        }

        $stock->update($stockData);

        return redirect()->route('stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $stock->delete();

        return redirect()->route('stocks.index')
            ->with('success', 'Stock deleted successfully.');
    }
}
