<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\FishCategory;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isDirector()) {
            $sales = Sale::with(['fishCategory', 'soldBy'])
                        ->orderBy('sale_date', 'desc')
                        ->get();
        } else {
            $sales = Sale::with(['fishCategory', 'soldBy'])
                        ->where('sold_by', $user->id)
                        ->orderBy('sale_date', 'desc')
                        ->get();
        }
        
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $categories = FishCategory::where('is_active', true)->get();
        return view('sales.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fish_category_id' => 'required|exists:fish_categories,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $category = FishCategory::findOrFail($request->fish_category_id);
        $totalAmount = $request->quantity * $category->unit_price;

        // Check available stock
        $availableStock = Stock::where('fish_category_id', $request->fish_category_id)
                               ->where('status', 'approved')
                               ->sum('quantity') - 
                               Sale::where('fish_category_id', $request->fish_category_id)
                                   ->sum('quantity');

        if ($availableStock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Insufficient stock. Available: ' . $availableStock,
            ])->withInput();
        }

        Sale::create([
            'fish_category_id' => $request->fish_category_id,
            'quantity' => $request->quantity,
            'unit_price' => $category->unit_price,
            'total_amount' => $totalAmount,
            'sold_by' => Auth::id(),
            'sale_date' => $request->sale_date,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'notes' => $request->notes,
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Sale recorded successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['fishCategory', 'soldBy']);
        return view('sales.show', compact('sale'));
    }

    public function report()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isDirector()) {
            abort(403, 'Unauthorized action.');
        }

        $sales = Sale::with(['fishCategory', 'soldBy'])
                    ->orderBy('sale_date', 'desc')
                    ->get();

        $totalSales = $sales->sum('total_amount');
        $totalQuantity = $sales->sum('quantity');
        
        $salesByCategory = $sales->groupBy('fish_category_id')
                              ->map(function ($categorySales) {
                                  return [
                                      'quantity' => $categorySales->sum('quantity'),
                                      'total' => $categorySales->sum('total_amount'),
                                      'category' => $categorySales->first()->fishCategory
                                  ];
                              });

        return view('sales.report', compact('sales', 'totalSales', 'totalQuantity', 'salesByCategory'));
    }

    public function destroy(Sale $sale)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
