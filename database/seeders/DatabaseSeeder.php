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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'instructor',
        ]);
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',

        ]);
         User::factory(10)->create(['role' => 'student']);
         User::factory(10)->create(['role' => 'instructor']);
        // call the CourseSeeder to seed courses
        $this->call(CategorySeeder::class);
        $this->call(CourseSeeder::class);


    }
}
