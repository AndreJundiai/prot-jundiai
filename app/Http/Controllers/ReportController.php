<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dentist;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Simple report view
        $dentists = Dentist::orderBy('name')->get();
        $stats = [
            'total_orders' => Order::count(),
            'finished_orders' => Order::where('status', 'Finalizado')->count(),
            'active_dentists' => Dentist::count(),
            'total_revenue' => \App\Models\FinancialRecord::where('type', 'debit')->sum('amount'),
        ];
        
        return view('reports.index', compact('dentists', 'stats'));
    }

    public function production()
    {
        $orders = Order::with(['dentist', 'patient'])
            ->whereIn('status', ['Em Produção', 'Atrasado'])
            ->orderBy('delivery_date', 'asc')
            ->get();
            
        return view('reports.production', compact('orders'));
    }

    public function dentists()
    {
        $dentists = Dentist::orderBy('name')->get();
        return view('reports.dentists', compact('dentists'));
    }
}
