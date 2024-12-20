<?php
namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Add this import
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::all()
            // Remove the auth checks for now since they're not set up
            // We can add them back after setting up proper policies
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'role' => ['required', 'string', Rule::in(['manager', 'crewoutlet', 'gudang'])],
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('manager.users.index');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['manager', 'crewoutlet', 'gudang'])],
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date',
            'password' => $request->filled('password') ? 'string|min:8|confirmed' : ''
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('manager.users.index');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active
        ]);
    }
}









