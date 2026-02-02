<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('pages.auth.signin');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Coba login dengan remember me option
        $remember = $request->has('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            if (auth()->user()->change_password) {
                return redirect()->route('password.change.form')
                    ->with('info', 'Silakan ubah password Anda terlebih dahulu.');
            }
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . auth()->user()->name . '!');
        }

        return back()
            ->withErrors([
                'username' => 'Username atau password salah.'
            ])
            ->withInput($request->except('password'));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}