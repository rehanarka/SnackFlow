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
            return redirect()->intended('/maps');
        }

        return back()->withErrors([
            'username' => 'Login failed. Please check your username and password.',
        ])->onlyInput('username');
    }
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        $availUser = User::where('email', $googleUser->getEmail())->first();
        if ($availUser){
            if (! $availUser->google_id){
                $availUser->google_id = $googleUser->getId();
                $availUser->save();
            }
        } else {
            $availUser = User::create([
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
            ]);
        }
        Auth::login($availUser);
        return redirect('/dashboard');
    }
}
