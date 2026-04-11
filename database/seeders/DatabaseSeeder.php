<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Identity\Enums\UserStatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            SpecializationSeeder::class,
            JobApplicationSeeder::class,
        ]);

        // 1. Create/Ensure Test Admin
        $admin = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Ahmed',
                'middle_name' => 'Yahia',
                'last_name' => 'Sharaf',
                'phone_number' => '01000000000',
                'date_of_birth' => '2004-01-01',
                'gender' => 'male',
                'country' => 'Egypt',
                'city' => 'Cairo',
                'password' => '123456789',
                'status' => UserStatus::ACTIVE,
            ]
        );
        $admin->assignRole('admin');

        // 2. Create 2 more Admins
        User::factory(2)->create()->each(function (User $u) {
            $u->assignRole('admin');
        });

        // 3. Create 10 Teachers
        User::factory(10)->create()->each(function (User $u) {
            $u->assignRole('teacher');
        });

        // 4. Create 37 Students
        User::factory(37)->create()->each(function (User $u) {
            $u->assignRole('student');
        });
    }
}
