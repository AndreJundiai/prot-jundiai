<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index(Request $request)
    {
        $query = Dentist::query();
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('cro', 'like', "%{$search}%");
        }

        $dentists = $query->orderBy('name')->paginate(10);
        
        return view('dentists.index', compact('dentists'));
    }

    public function create()
    {
        // Simple implementation without a separate create page for now, using a modal later or just a form
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

    public function edit(Dentist $dentist)
    {
        //
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
        $dentist->delete();
        return redirect()->route('dentists.index')->with('success', 'Dentista removido com sucesso!');
    }
}
