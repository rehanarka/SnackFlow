<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);
        return redirect('/')->with('success', 'Registration successful. Please Login.');
    }

    public function login(Request $request)
    {
        $pengecekan = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($pengecekan)){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Login failed. Please check your username and password.',
        ])->onlyInput('username');
    }
}