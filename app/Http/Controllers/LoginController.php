<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'success',
                'message' => 'successfully login'
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah!'
        ], 400);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
