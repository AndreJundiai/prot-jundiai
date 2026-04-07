<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $orders = Order::with(['dentist', 'patient'])
            ->whereIn('status', ['Aberto', 'Em Produção', 'Faltando'])
            ->orderBy('delivery_date', 'asc')
            ->get();
            
        $abertos = $orders->where('status', 'Aberto');
        $emProducao = $orders->where('status', 'Em Produção');
        $faltando = $orders->where('status', 'Faltando');

        return view('production.index', compact('abertos', 'emProducao', 'faltando'));
    }
}
