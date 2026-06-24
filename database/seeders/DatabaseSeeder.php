<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@snackflow.com',
        ], [
            'nama_lengkap' => 'Admin SnackFlow',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
