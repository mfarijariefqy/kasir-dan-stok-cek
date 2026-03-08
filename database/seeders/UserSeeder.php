<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create Kasir User
        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@demo.com',
            'password' => Hash::make('password'),
        ]);
        $kasir->assignRole('kasir');
    }
}
