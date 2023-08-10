<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            SystemUserSeeder::class,
            RoleSeeder::class,
            DocumentTypeSeeder::class,
            //EmployeeSeeder::class,
            //UserSeeder::class,
            CourseSeeder::class,
            PoleSeeder::class,
            //BondSeeder::class,
            //ApprovedSeeder::class,
            //DocumentSeeder::class,
            //DocumentSeeder::class,
            //DummyEmployeeSeeder::class,
            //ResponsibilitySeeder::class
        ]);

        if (App::Environment() != 'production') {
            $this->call([
                EmployeeSeeder::class,
                UserSeeder::class,
                BondSeeder::class,
                ApplicantSeeder::class,
                DocumentSeeder::class,
                DummyEmployeeSeeder::class,
                ResponsibilitySeeder::class,
                CourseClassSeeder::class,
                BondCourseSeeder::class,
            ]);
        }

        if (App::Environment() === 'production') {
            $this->call([
                ProdAdminSeeder::class,
            ]);
        }
    }
    // public function run(): void
    // {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    // }
}
