<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('category')->orderBy('name')->get();
        return view('products.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        Service::create($validated);

        return redirect()->route('products.index')->with('success', 'Serviço cadastrado com sucesso!');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        $service->update($validated);

        return redirect()->route('products.index')->with('success', 'Serviço atualizado!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('products.index')->with('success', 'Serviço removido!');
    }
}
