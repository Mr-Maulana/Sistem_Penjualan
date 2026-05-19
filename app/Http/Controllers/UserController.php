<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->get();
        $unlinkedSalesmen = \App\Models\Salesman::whereNotIn('id', User::whereNotNull('salesman_id')->pluck('salesman_id'))
            ->whereNotIn('email', User::pluck('email'))
            ->get();
        return view('user.index', compact('users', 'unlinkedSalesmen'));
    }

    public function create()
    {
        $roles = ['admin', 'manager', 'supervisor', 'sales'];
        $unlinkedSalesmen = \App\Models\Salesman::whereNotIn('id', User::whereNotNull('salesman_id')->pluck('salesman_id'))
            ->whereNotIn('email', User::pluck('email'))
            ->get();
        return view('user.form', compact('roles', 'unlinkedSalesmen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,manager,supervisor,sales',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'nullable|string|max:16',
            'nip' => 'nullable|string|max:20',
            'profesi' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'salesman_id' => 'nullable|exists:salesmen,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'nik' => $validated['nik'],
            'nip' => $validated['nip'],
            'profesi' => $validated['profesi'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'salesman_id' => $validated['salesman_id'] ?? null,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'manager', 'supervisor', 'sales'];
        
        // Unlinked salesmen + the one linked to this user (if any)
        $unlinkedSalesmen = \App\Models\Salesman::whereNotIn('id', 
            User::whereNotNull('salesman_id')
                ->where('id', '!=', $user->id)
                ->pluck('salesman_id')
        )->whereNotIn('email', 
            User::where('id', '!=', $user->id)
                ->pluck('email')
        )->get();

        return view('user.form', compact('user', 'roles', 'unlinkedSalesmen'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,supervisor,sales',
            'password' => 'nullable|string|min:8|confirmed',
            'nik' => 'nullable|string|max:16',
            'nip' => 'nullable|string|max:20',
            'profesi' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'salesman_id' => 'nullable|exists:salesmen,id',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'nik' => $validated['nik'],
            'nip' => $validated['nip'],
            'profesi' => $validated['profesi'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'salesman_id' => $validated['salesman_id'] ?? null,
        ];
        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    public function storeSalesmanAccount(Request $request)
    {
        $validated = $request->validate([
            'salesman_id' => 'required|exists:salesmen,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $salesman = \App\Models\Salesman::findOrFail($validated['salesman_id']);

        if (User::where('email', $salesman->email)->exists()) {
            return redirect()->route('user.index')->with('error', 'Email salesman (' . $salesman->email . ') sudah digunakan oleh user lain.');
        }

        if (User::where('salesman_id', $salesman->id)->exists()) {
            return redirect()->route('user.index')->with('error', 'Salesman ini sudah memiliki akun user.');
        }

        $role = 'sales';
        if ($salesman->level === 'manager') {
            $role = 'manager';
        } elseif ($salesman->level === 'supervisor') {
            $role = 'supervisor';
        }

        User::create([
            'name' => $salesman->name,
            'email' => $salesman->email,
            'role' => $role,
            'password' => Hash::make($validated['password']),
            'nik' => $salesman->nik,
            'phone' => $salesman->phone,
            'address' => $salesman->address,
            'salesman_id' => $salesman->id,
            'profesi' => 'Salesman ' . ucfirst($salesman->level),
        ]);

        return redirect()->route('user.index')->with('success', 'Akun user untuk salesman ' . $salesman->name . ' berhasil dibuat');
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

