<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\BankAccount;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Course;
use App\Models\Bond;
use App\Models\BondCourse;
use App\Models\BondPole;
use App\Models\Identity;
use App\Models\InstitutionalDetail;
use App\Models\PersonalDetail;
use App\Models\Phone;
use App\Models\Role;
use App\Models\Pole;

class DummyEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* "Coordenador de Curso"
         *
         * - each course has only one coordinator
         * - coordinators only have one course
         */
        foreach (Course::all() as $course) {
            $role = Role::where('name', 'like', 'Coordenador de curso%')->get()->random();

            $employee = Employee::factory()
                // ->assumePopulatedDatabase()
                ->create();

            PersonalDetail::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Phone::factory()->makeLandline()
                ->create([
                    'employee_id' => $employee->id,
                ])->make;

            Phone::factory()->makeMobile()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Address::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            BankAccount::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            InstitutionalDetail::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Identity::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            $bond = Bond::factory()->create([
                'employee_id' => $employee,
                'role_id' => $role,
            ]);

            BondCourse::create([
                'bond_id' => $bond->getAttribute('id'),
                'course_id' => $course->getAttribute('id'),
            ]);
        }


        /*
         *  "Tutores Presenciais" (um por polo)
         *
         *  - each course has a 'tutor presencial' in each pole.
         *  - so 20 courses x 20 tutors = 400 tutors.
         *  - we'll limit to 3 courses and 3 poles (3 x 3 = 9, which is manageable)
         */
        $poles   = Pole::where('name', '!=', 'SEAD')->take(3)->get();
        $courses = Course::take(3)->get();
        $role = Role::where('name', 'Tutor Presencial')->first();

        foreach ($courses as $course) {
            foreach ($poles as $pole) {
                $employee = Employee::factory()
                    // ->assumePopulatedDatabase()
                    ->create();

                PersonalDetail::factory()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                Phone::factory()->makelandline()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                Phone::factory()->makeMobile()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                Address::factory()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                BankAccount::factory()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                InstitutionalDetail::factory()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                Identity::factory()
                    ->create([
                        'employee_id' => $employee->id,
                    ]);

                $bond = Bond::factory()->create([
                    'employee_id' => $employee,
                    'role_id' => $role,
                ]);

                BondCourse::create([
                    'bond_id' => $bond->getAttribute('id'),
                    'course_id' => $course->getAttribute('id'),
                ]);

                BondPole::create([
                    'bond_id' => $bond->getAttribute('id'),
                    'pole_id' => $pole->getAttribute('id'),
                ]);
            }
        }

        /*
         *  "Tutores a distância" (um por curso)
         */
        foreach (Course::all() as $course) {
            $role = Role::where('name', 'Tutor a Distancia')->first();

            $employee = Employee::factory()
                // ->assumePopulatedDatabase()
                ->create();

            PersonalDetail::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Phone::factory()->makeLandline()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Phone::factory()->makeMobile()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Address::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            BankAccount::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            InstitutionalDetail::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            Identity::factory()
                ->create([
                    'employee_id' => $employee->id,
                ]);

            $bond = Bond::factory()->create([
                'employee_id' => $employee,
                'role_id' => $role,
            ]);

            BondCourse::create([
                'bond_id' => $bond->getAttribute('id'),
                'course_id' => $course->getAttribute('id'),
            ]);
        }
    }
}
