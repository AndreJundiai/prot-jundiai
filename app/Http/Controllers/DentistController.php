<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        $dentists = Dentist::with('prices')->orderBy('name')->get();
        // Load all services to show in the price management modal/section
        $services = \App\Models\Service::orderBy('name')->get();
        return view('dentists.index', compact('dentists', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cro' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Dentist::create($validated);

        return redirect()->route('dentists.index')->with('success', 'Dentista cadastrado com sucesso!');
    }

    public function update(Request $request, Dentist $dentist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cro' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $dentist->update($validated);

        return redirect()->route('dentists.index')->with('success', 'Dentista atualizado com sucesso!');
    }

    public function destroy(Dentist $dentist)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-data');
        $dentist->delete();
        return redirect()->route('dentists.index')->with('success', 'Dentista removido com sucesso!');
    }

    public function updatePrices(Request $request, Dentist $dentist)
    {
        $prices = $request->input('prices', []);
        
        foreach ($prices as $serviceId => $price) {
            if ($price !== null && $price !== '') {
                \App\Models\DentistPrice::updateOrCreate(
                    ['dentist_id' => $dentist->id, 'service_id' => $serviceId],
                    ['price' => str_replace(',', '.', $price)]
                );
            }
        }
        
        return back()->with('success', 'Tabela de preços atualizada!');
    }

    public function getPrice(Dentist $dentist, \App\Models\Service $service)
    {
        $customPrice = \App\Models\DentistPrice::where('dentist_id', $dentist->id)
            ->where('service_id', $service->id)
            ->first();
            
        return response()->json([
            'price' => $customPrice ? $customPrice->price : $service->base_price
        ]);
    }
}
