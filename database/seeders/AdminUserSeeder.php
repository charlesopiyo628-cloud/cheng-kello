<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'System Admin',
            'email' => 'admin@fishsales.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+256123456789',
            'address' => 'Kampala, Uganda',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Test Director',
            'email' => 'director@fishsales.com',
            'password' => \Illuminate\Support\Facades\Hash::make('director123'),
            'role' => 'director',
            'phone' => '+256987654321',
            'address' => 'Entebbe, Uganda',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Test Cashier',
            'email' => 'cashier@fishsales.com',
            'password' => \Illuminate\Support\Facades\Hash::make('cashier123'),
            'role' => 'cashier',
            'phone' => '+256112233445',
            'address' => 'Jinja, Uganda',
            'is_active' => true,
        ]);
    }
}
