<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        if (User::where('email', 'admin@banque.test')->exists()) {
            return;
        }
        User::create([
            'name' => 'Admin Banque',
            'email' => 'admin@banque.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
