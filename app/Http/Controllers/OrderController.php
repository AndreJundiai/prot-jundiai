<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\FinancialRecord;
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
            'via' => 'nullable|string|max:50',
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
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido excluído com sucesso!');
    }

    public function os(Order $order)
    {
        $order->load(['dentist', 'patient', 'technicalRecord']);
        
        // Calculate balance for the dentist
        $transactions = \App\Models\FinancialRecord::where('dentist_id', $order->dentist_id)
            ->orderBy('transaction_date', 'asc')
            ->get();
            
        $totalCredits = $transactions->where('type', 'credit')->sum('amount');
        $totalDebits = $transactions->where('type', 'debit')->sum('amount');
        $currentBalance = $totalDebits - $totalCredits;
        // Previous balance (balance before this order was invoiced)
        // If this order is not yet invoiced, the current balance IS the previous balance
        // If it is invoiced, we subtract its price from the current total debits to get previous
        $previousBalance = $order->is_invoiced ? ($currentBalance - $order->price) : $currentBalance;
        
        // Last payment
        $lastPayment = \App\Models\FinancialRecord::where('dentist_id', $order->dentist_id)
            ->where('type', 'credit')
            ->orderBy('transaction_date', 'desc')
            ->first();

        return view('orders.os', compact('order', 'previousBalance', 'currentBalance', 'lastPayment'));
    }
}
