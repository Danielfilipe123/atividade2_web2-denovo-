<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {

        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        
    $this->authorize('update', $user);

    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'role' => 'nullable|in:cliente,bibliotecario,admin',
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    if ($request->user()->can('editRole', $user) && isset($validated['role'])) {
        $user->role = $validated['role'];
    }

    $user->save();
        $user->update($request->only('name', 'email'));
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
