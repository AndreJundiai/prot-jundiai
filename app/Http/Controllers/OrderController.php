<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dentist;
use App\Models\Patient;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['dentist', 'patient']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('delivery_date', 'asc')->paginate(15);
        
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $dentists = Dentist::orderBy('name')->get();
        $patients = Patient::orderBy('name')->get();
        $services = \App\Models\Service::orderBy('name')->get();
        return view('orders.create', compact('dentists', 'patients', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'patient_id' => 'nullable|exists:patients,id',
            'status' => 'required|in:Aberto,Em Produção,Finalizado,Faltando',
            'delivery_date' => 'nullable|date',
            'price' => 'nullable|numeric',
            'service_name' => 'nullable|string|max:255',
            'via' => 'nullable|string|max:50',
        ]);

        $order = Order::create($validated);

        // Create technical record stub
        $order->technicalRecord()->create(['order_id' => $order->id]);

        return redirect()->route('orders.index')->with('success', 'Pedido criado com sucesso!');
    }

    public function show(Order $order)
    {
        $order->load(['dentist', 'patient', 'technicalRecord']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $dentists = Dentist::orderBy('name')->get();
        $patients = Patient::orderBy('name')->get();
        $services = \App\Models\Service::orderBy('name')->get();
        return view('orders.edit', compact('order', 'dentists', 'patients', 'services'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'patient_id' => 'nullable|exists:patients,id',
            'status' => 'required|in:Aberto,Em Produção,Finalizado,Faltando',
            'delivery_date' => 'nullable|date',
            'price' => 'nullable|numeric',
            'service_name' => 'nullable|string|max:255',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);

        // Automate Financial Record Creation
        if ($order->status === 'Finalizado' && $oldStatus !== 'Finalizado' && !$order->is_invoiced) {
            \App\Models\FinancialRecord::create([
                'dentist_id' => $order->dentist_id,
                'order_id' => $order->id,
                'amount' => $order->price ?? 0,
                'type' => 'debit',
                'description' => $order->service_name ?? 'Serviço de Prótese',
                'patient_name' => $order->patient ? $order->patient->name : null,
                'transaction_date' => now(),
            ]);

            $order->update(['is_invoiced' => true]);
        }

        return redirect()->route('orders.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido excluído com sucesso!');
    }

    public function os(Order $order)
    {
        $order->load(['dentist', 'patient', 'technicalRecord']);
        return view('orders.os', compact('order'));
    }
}
