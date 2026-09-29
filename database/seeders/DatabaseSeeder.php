<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create the default admin account used to sign in to /admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@casaverde.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Create Casa Verde homepage content
        $this->call([
            HomeContentSeeder::class,
        ]);
    }
}
