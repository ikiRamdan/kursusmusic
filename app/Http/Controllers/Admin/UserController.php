<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 🎯 FILTER ROLE
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // 🎯 FILTER STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'role' => 'required|in:admin,owner,kasir',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        // 🧠 ACTIVITY LOG
        logActivity(
            'CREATE USER',
            'Menambahkan user: ' . $user->name . ' (' . $user->email . ')'
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,owner,kasir',
            'status' => 'required|in:active,inactive',
        ]);

        $oldData = $user->only(['name', 'email', 'role', 'status']);

        $data = $request->only(['name', 'email', 'role', 'status']);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // 🧠 ACTIVITY LOG (dengan perubahan)
        logActivity(
            'UPDATE USER',
            'Update user: ' . $user->name .
            ' | Sebelum: ' . json_encode($oldData) .
            ' | Sesudah: ' . json_encode($data)
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $email = $user->email;

        $user->delete();

        // 🧠 ACTIVITY LOG
        logActivity(
            'DELETE USER',
            'Menghapus user: ' . $name . ' (' . $email . ')'
        );

        return back()->with('success', 'User berhasil dihapus');
    }
}