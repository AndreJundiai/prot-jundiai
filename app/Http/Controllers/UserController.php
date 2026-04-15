<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-admins');
        
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-admins');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,worker',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('status', 'Administrador/Usuário criado com sucesso!');
    }

    public function destroy(User $user)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage-admins');
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Você não pode excluir a si mesmo.']);
        }

        $user->delete();
        return redirect()->route('users.index')->with('status', 'Usuário removido com sucesso!');
    }
}
