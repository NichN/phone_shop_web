<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create basic roles if they don't exist
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Staff'],
            ['id' => 3, 'name' => 'Delivery'],
            ['id' => 4, 'name' => 'Customer'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name']]
            );
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
