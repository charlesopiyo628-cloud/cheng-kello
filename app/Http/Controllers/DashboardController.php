<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\User;
use App\Models\FishCategory;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics based on user role
        $stats = [];
        
        if ($user->isAdmin()) {
            $stats['total_users'] = User::count();
            $stats['pending_stocks'] = Stock::where('status', 'pending')->count();
            $stats['total_sales_today'] = Sale::whereDate('sale_date', today())->sum('total_amount');
            $stats['total_purchases_today'] = Purchase::whereDate('purchase_date', today())->sum('total_cost');
            $stats['total_sales'] = Sale::sum('total_amount');
            $stats['total_purchases'] = Purchase::sum('total_cost');
            
            // Stock statistics
            $stats['total_stock'] = Stock::where('status', 'approved')->sum('quantity');
            $stats['total_sold'] = Sale::sum('quantity');
            $stats['remaining_stock'] = $stats['total_stock'] - $stats['total_sold'];
            $stats['low_stock_categories'] = $this->getLowStockCategories();
            
            $recentSales = Sale::with(['fishCategory', 'soldBy'])
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();
            
            $pendingStocks = Stock::with(['fishCategory', 'addedBy'])
                                 ->where('status', 'pending')
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get();
            
            return view('dashboard.admin', compact('stats', 'recentSales', 'pendingStocks'));
            
        } elseif ($user->isDirector()) {
            $stats['total_sales_today'] = Sale::whereDate('sale_date', today())->sum('total_amount');
            $stats['total_sales'] = Sale::sum('total_amount');
            $stats['total_sales_quantity'] = Sale::sum('quantity');
            
            // Stock statistics
            $stats['total_stock'] = Stock::where('status', 'approved')->sum('quantity');
            $stats['total_sold'] = Sale::sum('quantity');
            $stats['remaining_stock'] = $stats['total_stock'] - $stats['total_sold'];
            $stats['low_stock_categories'] = $this->getLowStockCategories();
            
            $recentSales = Sale::with(['fishCategory', 'soldBy'])
                              ->orderBy('created_at', 'desc')
                              ->limit(10)
                              ->get();
            
            return view('dashboard.director', compact('stats', 'recentSales'));
            
        } else { // Cashier
            $stats['my_sales_today'] = Sale::where('sold_by', $user->id)
                                          ->whereDate('sale_date', today())
                                          ->sum('total_amount');
            $stats['my_sales'] = Sale::where('sold_by', $user->id)->sum('total_amount');
            $stats['my_sales_quantity'] = Sale::where('sold_by', $user->id)->sum('quantity');
            
            // Stock statistics for cashier
            $stats['total_stock'] = Stock::where('status', 'approved')->sum('quantity');
            $stats['total_sold'] = Sale::sum('quantity');
            $stats['remaining_stock'] = $stats['total_stock'] - $stats['total_sold'];
            
            $myStocks = Stock::with(['fishCategory'])
                            ->where('added_by', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
            
            $mySales = Sale::with(['fishCategory'])
                            ->where('sold_by', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
            
            return view('dashboard.cashier', compact('stats', 'myStocks', 'mySales'));
        }
    }
    
    private function getLowStockCategories()
    {
        $categories = FishCategory::where('is_active', true)->get();
        $lowStock = [];
        
        foreach ($categories as $category) {
            $totalApproved = Stock::where('fish_category_id', $category->id)
                               ->where('status', 'approved')
                               ->sum('quantity');
            $totalSold = Sale::where('fish_category_id', $category->id)
                             ->sum('quantity');
            $remaining = $totalApproved - $totalSold;
            
            if ($remaining <= 10) { // Low stock threshold
                $lowStock[] = [
                    'category' => $category->name,
                    'remaining' => $remaining
                ];
            }
        }
        
        return $lowStock;
    }
}
