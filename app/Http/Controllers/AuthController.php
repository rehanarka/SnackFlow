<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|unique:user,email',
            'password' => 'required|min:6',
        ],
        [
            'nama_lengkap.required' => 'Input tidak boleh kosong.',
            'email.required' => 'Input tidak boleh kosong.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'email' => $request->email,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        return redirect('/registrasi')->with('success', 'Registration successful. Please Login.');
    }

    public function login(Request $request)
    {
        $pengecekan = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ],
        [
            'email.required' => 'Input tidak boleh kosong.',
            'email.email' => 'Email tidak valid.',
            'password.required' => 'Input tidak boleh kosong.',
        ]);

        if (Auth::attempt($pengecekan)){
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'admin'){
                return redirect()->route('admin.katalog');
            }
            else{
                return redirect()->route('user.katalog');
            }
        }
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landingPage');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_lengkap.required' => 'Data Tidak Sesuai.',
            'email.required' => 'Data Tidak Sesuai.',
            'email.email' => 'Data Tidak Sesuai.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
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

        return back()->with('success', 'Data berhasil diubah.');
    }
}
