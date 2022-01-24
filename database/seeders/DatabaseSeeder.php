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
            GrantTypeSeeder::class,
            UserTypeSeeder::class,
            RoleSeeder::class,
            GenderSeeder::class,
            StateSeeder::class,
            DocumentTypeSeeder::class,
            MaritalStatusSeeder::class,
            //EmployeeSeeder::class,
            //UserSeeder::class,
            CourseTypeSeeder::class,
            CourseSeeder::class,
            PoleSeeder::class,
            //BondSeeder::class,
            ApprovedStateSeeder::class,
            //ApprovedSeeder::class,
            //EmployeeDocumentSeeder::class,
            //BondDocumentSeeder::class,
            //DocumentSeeder::class,
            //DummyEmployeeSeeder::class,
            //UserTypeAssignmentSeeder::class
        ]);

        if (App::Environment() != 'production') {
            $this->call([
                EmployeeSeeder::class,
                UserSeeder::class,
                BondSeeder::class,
                ApprovedSeeder::class,
                EmployeeDocumentSeeder::class,
                BondDocumentSeeder::class,
                DocumentSeeder::class,
                DummyEmployeeSeeder::class,
                UserTypeAssignmentSeeder::class
            ]);
        }

        if (App::Environment() === 'production') {
            $this->call([
                ProdAdminSeeder::class,
            ]);
        }
    }
}
