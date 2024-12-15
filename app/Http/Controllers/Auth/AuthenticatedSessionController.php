<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        $credentials = array_merge(
            $request->only('username', 'password'),
            ['is_active' => 1]
        );
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Verifikasi role
            if (!$user || !in_array($user->role, ['manager', 'crewoutlet', 'gudang'])) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Invalid role access.',
                ]);
            }
            
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            switch($user->role) {
                case 'manager':
                    return redirect()->route('manager.dashboard');
                case 'crewoutlet':
                    return redirect()->route('crew.dashboard');
                case 'gudang':
                    return redirect()->route('gudang.dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }
    
        return back()->withErrors([
            'username' => 'Invalid credentials or account is inactive.',
        ]);
    }
    public function create()
    {
        return Inertia::render('Auth/Login');
    }
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}