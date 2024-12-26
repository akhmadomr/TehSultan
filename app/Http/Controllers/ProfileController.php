<?php

namespace App\Http\Controllers;

use App\Models\PendingUpdate;
use App\Notifications\UpdateVerificationNotification;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'alamat' => ['required', 'string'],
            'no_telp' => ['required', 'string'],
        ]);

        // Create pending update
        $token = Str::random(64);
        $pendingUpdate = PendingUpdate::create([
            'user_id' => $user->id,
            'data' => $validated,
            'type' => 'profile',
            'verification_token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        // Send verification email
        $user->notify(new UpdateVerificationNotification($token, 'profile'));

        return Redirect::route('profile.edit')
            ->with('status', 'verification-link-sent');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $token = Str::random(64);
        $pendingUpdate = PendingUpdate::create([
            'user_id' => $request->user()->id,
            'data' => ['password' => Hash::make($validated['password'])],
            'type' => 'password',
            'verification_token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        $request->user()->notify(new UpdateVerificationNotification($token, 'password'));

        return Redirect::back()->with('status', 'verification-link-sent');
    }

    public function verifyUpdate(string $token): RedirectResponse
    {
        $pendingUpdate = PendingUpdate::where('verification_token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        $user = $pendingUpdate->user;
        $updateData = $pendingUpdate->data;

        switch ($pendingUpdate->type) {
            case 'profile':
                $user->update($updateData);
                break;
                
            case 'password':
                if (isset($updateData['password'])) {
                    $user->update([
                        'password' => $updateData['password']
                    ]);
                }
                break;

            case 'user_management':
                // Remove password if not set to avoid errors
                if (!isset($updateData['password'])) {
                    unset($updateData['password']);
                }
                $user->update($updateData);
                break;
        }

        $pendingUpdate->delete();

        return Redirect::route('profile.edit')
            ->with('status', 'update-verified');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
