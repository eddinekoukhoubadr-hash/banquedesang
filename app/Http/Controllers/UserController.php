<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $r->name,
            'email' => $r->email,
            'role' => $r->role,
            'password' => Hash::make($r->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $r, $id)
{
    $user = User::findOrFail($id);

    // Validation seulement pour le mot de passe
    $r->validate([
        'password' => 'nullable|min:6|confirmed',
    ]);

    // Mettre à jour seulement le mot de passe si fourni
    if (!empty($r->password)) {
        $user->password = Hash::make($r->password);
        $user->save();
    }

    return redirect()->route('users.index')->with('success', 'Mot de passe modifié avec succès.');
}

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) $user->delete();

        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function updatePassword(Request $r)
    {
        $r->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = auth()->user();

        if (!Hash::check($r->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = Hash::make($r->password);
        $user->save();

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }
}
