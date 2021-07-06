<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    protected $tableName = 'courses';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Curso de Letras',
            'description' => 'Curso de Letras',
            'begin' => Carbon::create('2020', '01', '01'),
            'end' => Carbon::create('2027', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Curso de Física',
            'description' => 'Curso de Física',
            'begin' => Carbon::create('2021', '01', '01'),
            'end' => Carbon::create('2028', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Curso de Química',
            'description' => 'Curso de Química',
            'begin' => Carbon::create('2021', '06', '01'),
            'end' => Carbon::create('2028', '12', '01'),
        ]);
    }
}
