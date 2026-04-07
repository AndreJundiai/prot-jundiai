<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TechnicalRecord;
use Illuminate\Http\Request;

class TechnicalRecordController extends Controller
{
    /**
     * Show the form for editing the technical record for a given order.
     * If it doesn't exist, it will be implicitly created upon saving.
     */
    public function edit(Order $order)
    {
        $order->load(['dentist', 'patient', 'technicalRecord']);
        
        // Ensure an empty record object exists just for form binding if none exists
        $technicalRecord = $order->technicalRecord ?? new TechnicalRecord(['order_id' => $order->id]);
        
        return view('technical_records.edit', compact('order', 'technicalRecord'));
    }

    /**
     * Update or Create the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'finish' => 'nullable|string|max:255',
            'occlusion' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'teeth' => 'nullable|string', // JSON string from odontogram
        ]);

        // Decode the teeth JSON string into array for storage
        if (isset($validated['teeth'])) {
            $validated['teeth'] = json_decode($validated['teeth'], true) ?? [];
        }

        if ($order->technicalRecord) {
            $order->technicalRecord->update($validated);
        } else {
            $order->technicalRecord()->create($validated);
        }

        return redirect()->route('orders.index')->with('success', 'Ficha Técnica do pedido #' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ' salva com sucesso!');
    }
}
