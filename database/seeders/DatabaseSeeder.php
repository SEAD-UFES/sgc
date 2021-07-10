<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            GrantTypeSeeder::class,
            UserTypeSeeder::class,
            RoleSeeder::class,
            GenderSeeder::class,
            StateSeeder::class,
            IdTypeSeeder::class,
            MaritalStatusSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
            CourseTypeSeeder::class,
            CourseSeeder::class,
            PoleSeeder::class,
        ]);
    }
}
