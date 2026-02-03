<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /* =====================
     | LIST USER
     ===================== */
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();

        return view('users.index', [
            'users' => User::orderBy('role')->orderBy('name')->get()
        ]);
    }

    /* =====================
     | CREATE
     ===================== */
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,staff',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()
            ->route('users.view.index')
            ->with('success', 'User berhasil dibuat');
    }

    /* =====================
     | EDIT
     ===================== */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,staff',
        ]);

        // PROTEKSI SUPER ADMIN
        if ($user->isSuperAdmin()) {
            unset($validated['role']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.view.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /* =====================
     | DELETE
     ===================== */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin tidak boleh dihapus');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
