<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function form()
    {
        return view('Auth.change_password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->change_password = false;
        $user->save();

        return redirect()->route('dashboard');
    }
}
