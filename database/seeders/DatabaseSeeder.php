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
            UserSeeder::class,
            SpecializationSeeder::class,
            JobApplicationSeeder::class,
            ProgramSeeder::class,
            GeneralBundleSeeder::class,
        ]);
    }
}
