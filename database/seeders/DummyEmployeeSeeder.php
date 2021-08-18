<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Course;
use App\Models\Bond;
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
        $pole = Pole::where('name', 'SEAD')->first();

        foreach (Course::all() as $course) {
            $role = Role::where('name', 'like', 'Coordenador de curso%')->get()->random();

            $employee = Employee::factory()
                ->assumePopulatedDatabase()
                ->create([
                    'job' => 'Coordenador de Curso',
                ]);

            Bond::factory()->create([
                'employee_id' => $employee,
                'course_id' => $course,
                'role_id' => $role,
                'pole_id' => $pole,
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
                    ->assumePopulatedDatabase()
                    ->create([
                        //'gender_id' => Gender::all()->random(),
                        'job' => 'Tutor Presencial',
                    ]);

                Bond::factory()->create([
                    'employee_id' => $employee,
                    'course_id' => $course,
                    'role_id' => $role,
                    'pole_id' => $pole,
                ]);
            }
        }
    }
}
