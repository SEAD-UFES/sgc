<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
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
}
