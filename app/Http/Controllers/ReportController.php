<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\FishCategory;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generateAllActivities()
    {
        $user = Auth::user();
        
        // Get all data for the report
        $data = [
            'report_date' => now()->format('d/m/Y'),
            'generated_by' => $user->name,
            'total_users' => User::count(),
            'total_categories' => FishCategory::where('is_active', true)->count(),
            'total_stock' => Stock::where('status', 'approved')->sum('quantity'),
            'total_sales' => Sale::sum('quantity'),
            'total_sales_amount' => Sale::sum('total_amount'),
            'total_purchases' => Purchase::sum('quantity'),
            'total_purchases_amount' => Purchase::sum('total_cost'),
            'remaining_stock' => Stock::where('status', 'approved')->sum('quantity') - Sale::sum('quantity'),
            'recent_sales' => Sale::with(['fishCategory', 'soldBy'])->orderBy('sale_date', 'desc')->limit(10)->get(),
            'recent_purchases' => Purchase::with(['fishCategory', 'createdBy'])->orderBy('purchase_date', 'desc')->limit(10)->get(),
            'pending_stocks' => Stock::with(['fishCategory', 'addedBy'])->where('status', 'pending')->get(),
        ];

        return view('reports.all_activities', compact('data'));
    }

    public function generateStockReport()
    {
        $data = [
            'report_date' => now()->format('d/m/Y'),
            'generated_by' => Auth::user()->name,
            'stocks' => Stock::with(['fishCategory', 'addedBy', 'approvedBy'])->get(),
            'total_approved' => Stock::where('status', 'approved')->sum('quantity'),
            'total_pending' => Stock::where('status', 'pending')->sum('quantity'),
            'total_rejected' => Stock::where('status', 'rejected')->sum('quantity'),
        ];

        return view('reports.stock', compact('data'));
    }

    public function generateSalesReport()
    {
        $data = [
            'report_date' => now()->format('d/m/Y'),
            'generated_by' => Auth::user()->name,
            'sales' => Sale::with(['fishCategory', 'soldBy'])->orderBy('sale_date', 'desc')->get(),
            'total_sales' => Sale::sum('quantity'),
            'total_amount' => Sale::sum('total_amount'),
            'today_sales' => Sale::whereDate('sale_date', today())->sum('total_amount'),
        ];

        return view('reports.sales', compact('data'));
    }

    public function generatePurchaseReport()
    {
        $data = [
            'report_date' => now()->format('d/m/Y'),
            'generated_by' => Auth::user()->name,
            'purchases' => Purchase::with(['fishCategory', 'createdBy'])->orderBy('purchase_date', 'desc')->get(),
            'total_purchases' => Purchase::sum('quantity'),
            'total_cost' => Purchase::sum('total_cost'),
            'today_purchases' => Purchase::whereDate('purchase_date', today())->sum('total_cost'),
        ];

        return view('reports.purchases', compact('data'));
    }
}
