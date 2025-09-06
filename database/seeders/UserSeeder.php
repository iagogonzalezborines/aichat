<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'tyrant@gmail.com'],
            [
                'name' => 'tyrant',
                'email' => 'tyrant@gmail.com',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}
