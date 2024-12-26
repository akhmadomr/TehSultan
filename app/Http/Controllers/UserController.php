<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PendingUpdate;
use App\Notifications\UpdateVerificationNotification;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Add this import
use Illuminate\Support\Str;
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

        // Only include password in validated data if it was provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        // Create verification token
        $token = Str::random(64);
        $expiresAt = now()->addHours(24);

        // Store pending update, without password if not provided
        PendingUpdate::create([
            'user_id' => $user->id,
            'data' => $validated,
            'type' => 'user_management',
            'verification_token' => $token,
            'expires_at' => $expiresAt,
        ]);

        // Send verification email
        $user->notify(new UpdateVerificationNotification($token, 'user management'));

        return response()->json([
            'message' => 'A verification link has been sent to your email address.'
        ]);
    }

    public function verifyUpdate($token)
    {
        $pendingUpdate = PendingUpdate::where('verification_token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$pendingUpdate) {
            return redirect()->route('manager.users.index')
                ->with('error', 'Invalid or expired verification token.');
        }

        $user = User::find($pendingUpdate->user_id);
        $updateData = $pendingUpdate->data;

        // If password is not set in the update data, remove it to avoid errors
        if (!isset($updateData['password'])) {
            unset($updateData['password']);
        }

        $user->update($updateData);
        $pendingUpdate->delete();

        return redirect()->route('manager.users.index')
            ->with('success', 'User information has been updated successfully.');
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









