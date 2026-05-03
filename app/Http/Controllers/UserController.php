<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin', 'supervisor', 'sales'];
        return view('user.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,supervisor,sales',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(string $id)
    {
        return redirect()->route('user.index');
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'supervisor', 'sales'];
        return view('user.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,supervisor,sales',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];
        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('user.index')->with('success', 'Tidak bisa menghapus user yang sedang login');
        }

        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}

