@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Generate Reports</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">📊 All Activities Report</h3>
                <p class="text-gray-600 mb-4">Complete overview of all system activities including users, stocks, sales, and purchases.</p>
                <a href="{{ route('reports.all_activities') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Generate Report
                </a>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-3">📦 Stock Report</h3>
                <p class="text-gray-600 mb-4">Detailed stock information including approved, pending, and rejected stocks.</p>
                <a href="{{ route('reports.stock') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Generate Report
                </a>
            </div>
            
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-3">💰 Sales Report</h3>
                <p class="text-gray-600 mb-4">Comprehensive sales data with totals and recent transactions.</p>
                <a href="{{ route('reports.sales') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Generate Report
                </a>
            </div>
            
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-orange-800 mb-3">🛒 Purchase Report</h3>
                <p class="text-gray-600 mb-4">Complete purchase history with cost analysis and supplier details.</p>
                <a href="{{ route('reports.purchases') }}" class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                    Generate Report
                </a>
            </div>
        </div>
        
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">📋 Report Instructions</h3>
            <ul class="text-gray-600 space-y-1">
                <li>• Click on any report to generate a printable version</li>
                <li>• Use your browser's print function (Ctrl+P) to save as PDF</li>
                <li>• Reports include real-time data from the system</li>
                <li>• All reports are accessible to authenticated users</li>
            </ul>
        </div>
    </div>
</div>
@endsection
