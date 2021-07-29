<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Gender;
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
        $role = Role::where('name', 'like', '%Coordenador%')->first();
        $pole = Pole::where('name', 'SEAD')->first();

        foreach (Course::all() as $course) {

            $employee = Employee::factory()
                ->create([
                    'gender_id' => Gender::all()->random(),
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
        *  "Tutores Presenciais" (um por polo) WIP
        *
        $role = Role::where('name', 'Tutor Presencial')->first();

        foreach(Pole::all() as $pole) {

        $employee = Employee::factory()
            ->create([
            'gender_id' => Gender::all()->random(),
            'job' => 'Tutor Presencial',
            ]);

        // TODO:
        //  find out if bond is supposed to accept course_id = null or not...
        //  there are conflicting narratives coming from people at the desk-secretaria-thing
        //
        Bond::factory()->create([
            'employee_id' => $employee,
            'course_id' => null, // <- null course is not allowed on the db
            'role_id' => $role,
            'pole_id' => $pole,
        ]);
        }
        */

        // TODO: 
        // #1 Each course has a coodinator (one per course)
        // #2 Each polo has a "tutor presencial" ???
        // #3 Each course has one "tutor a distancia" ???
    }
}
