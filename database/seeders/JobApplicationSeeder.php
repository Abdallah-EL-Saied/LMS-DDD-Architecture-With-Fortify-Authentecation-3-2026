<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\Models\JobApplication;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create some pending applications
        JobApplication::factory(10)->create();

        // 2. Create some approved applications
        JobApplication::factory(5)->approved()->create();

        // 3. Create some rejected applications
        JobApplication::factory(3)->rejected()->create();
    }
}
