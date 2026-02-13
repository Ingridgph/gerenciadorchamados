<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);

        User::factory()->create([
            'name' => 'User Comum',
            'email' => 'user@test.com',
        ]);
    }
}
