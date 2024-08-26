<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            ProductSeeder::class
        ]);

        User::create([
            'name' => 'Admin',
            'phone' => '998901234567',
        ])->assignRole('admin');

        User::create([
            'name' => 'User 1',
            'phone' => '998901234568',
        ])->assignRole('user');
    }
}
