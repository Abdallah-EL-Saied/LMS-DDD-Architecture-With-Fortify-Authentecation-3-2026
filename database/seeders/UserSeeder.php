<?php
 
namespace Database\Seeders;
 
use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Domains\Identity\Enums\UserStatus;
use Illuminate\Database\Seeder;
 
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
 
        // 2. Create more Admins
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
