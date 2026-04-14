<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ],
        [
            'name.required' => 'Nama tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);
        return redirect('/')->with('success', 'Registration successful. Please Login.');
    }

    public function login(Request $request)
    {
        $pengecekan = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ],
        [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        if (Auth::attempt($pengecekan)){
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'admin'){
                return redirect()->route('admin.dashboard');
            }
            else{
                return redirect()->route('user.dashboard');
            }
        }
        return back()->withErrors([
            'email' => 'Login gagal, pastikan email dan password anda benar.',
        ])->onlyInput('email');
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
                'role' => 'user',
            ]);
        }
        Auth::login($availUser);
        if ($availUser->role === 'admin'){
            return redirect()->route('admin.dashboard');
        }
        else{
            return redirect()->route('user.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
            'avatar.image' => 'Foto profile harus berupa gambar.',
            'avatar.mimes' => 'Foto profile harus berformat JPG, JPEG, PNG, atau WEBP.',
            'avatar.max' => 'Foto profile tidak boleh lebih dari 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if (!empty($user->avatar) && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatar', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}
