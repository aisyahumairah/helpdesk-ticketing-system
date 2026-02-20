<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CodeSeeder::class,
            RoleSeeder::class,
            RolesAndPermissionsSeeder::class,
            MailTemplatesSeeder::class
        ]);

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'aisyahumairah0412@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        $admin->assignRole('admin');
    }
}
