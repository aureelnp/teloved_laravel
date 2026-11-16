<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's users.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Teloved',
            'email' => 'admin@teloved.test',
        ]);

        User::factory()->count(10)->create();
    }
}
