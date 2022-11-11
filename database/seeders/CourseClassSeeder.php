<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseClassSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'course_classes';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert(['course_id' => 1, 'code' => 'ABC-001', 'name' => 'Disciplina 01', 'cpp' => 'CPP-001']);
        DB::table($this->tableName)->insert(['course_id' => 2, 'code' => 'ABC-002', 'name' => 'Disciplina 02', 'cpp' => 'CPP-002']);
        DB::table($this->tableName)->insert(['course_id' => 3, 'code' => 'ABC-003', 'name' => 'Disciplina 03', 'cpp' => 'CPP-003']);
        DB::table($this->tableName)->insert(['course_id' => 4, 'code' => 'ABC-004', 'name' => 'Disciplina 04', 'cpp' => 'CPP-004']);
        DB::table($this->tableName)->insert(['course_id' => 5, 'code' => 'ABC-005', 'name' => 'Disciplina 05', 'cpp' => 'CPP-005']);
        DB::table($this->tableName)->insert(['course_id' => 6, 'code' => 'ABC-006', 'name' => 'Disciplina 06', 'cpp' => 'CPP-006']);
        DB::table($this->tableName)->insert(['course_id' => 7, 'code' => 'ABC-007', 'name' => 'Disciplina 07', 'cpp' => 'CPP-007']);
        DB::table($this->tableName)->insert(['course_id' => 8, 'code' => 'ABC-008', 'name' => 'Disciplina 08', 'cpp' => 'CPP-008']);
        DB::table($this->tableName)->insert(['course_id' => 9, 'code' => 'ABC-009', 'name' => 'Disciplina 09', 'cpp' => 'CPP-009']);
        DB::table($this->tableName)->insert(['course_id' => 10, 'code' => 'ABC-010', 'name' => 'Disciplina 10', 'cpp' => 'CPP-010']);
        DB::table($this->tableName)->insert(['course_id' => 11, 'code' => 'ABC-011', 'name' => 'Disciplina 11', 'cpp' => 'CPP-011']);
        DB::table($this->tableName)->insert(['course_id' => 12, 'code' => 'ABC-012', 'name' => 'Disciplina 12', 'cpp' => 'CPP-012']);
        DB::table($this->tableName)->insert(['course_id' => 13, 'code' => 'ABC-013', 'name' => 'Disciplina 13', 'cpp' => 'CPP-013']);
        DB::table($this->tableName)->insert(['course_id' => 14, 'code' => 'ABC-014', 'name' => 'Disciplina 14', 'cpp' => 'CPP-014']);
        DB::table($this->tableName)->insert(['course_id' => 15, 'code' => 'ABC-015', 'name' => 'Disciplina 15', 'cpp' => 'CPP-015']);
        DB::table($this->tableName)->insert(['course_id' => 16, 'code' => 'ABC-016', 'name' => 'Disciplina 16', 'cpp' => 'CPP-016']);
        DB::table($this->tableName)->insert(['course_id' => 17, 'code' => 'ABC-017', 'name' => 'Disciplina 17', 'cpp' => 'CPP-017']);
        DB::table($this->tableName)->insert(['course_id' => 18, 'code' => 'ABC-018', 'name' => 'Disciplina 18', 'cpp' => 'CPP-018']);
        DB::table($this->tableName)->insert(['course_id' => 19, 'code' => 'ABC-019', 'name' => 'Disciplina 19', 'cpp' => 'CPP-019']);
        DB::table($this->tableName)->insert(['course_id' => 20, 'code' => 'ABC-020', 'name' => 'Disciplina 20', 'cpp' => 'CPP-020']);
        DB::table($this->tableName)->insert(['course_id' => 21, 'code' => 'ABC-021', 'name' => 'Disciplina 21', 'cpp' => 'CPP-021']);
        DB::table($this->tableName)->insert(['course_id' => 22, 'code' => 'ABC-022', 'name' => 'Disciplina 22', 'cpp' => 'CPP-022']);
        DB::table($this->tableName)->insert(['course_id' => 23, 'code' => 'ABC-023', 'name' => 'Disciplina 23', 'cpp' => 'CPP-023']);
        DB::table($this->tableName)->insert(['course_id' => 1, 'code' => 'ABC-024', 'name' => 'Disciplina 24', 'cpp' => 'CPP-024']);
        DB::table($this->tableName)->insert(['course_id' => 2, 'code' => 'ABC-025', 'name' => 'Disciplina 25', 'cpp' => 'CPP-025']);
        DB::table($this->tableName)->insert(['course_id' => 3, 'code' => 'ABC-026', 'name' => 'Disciplina 26', 'cpp' => 'CPP-026']);
        DB::table($this->tableName)->insert(['course_id' => 4, 'code' => 'ABC-027', 'name' => 'Disciplina 27', 'cpp' => 'CPP-027']);
        DB::table($this->tableName)->insert(['course_id' => 5, 'code' => 'ABC-028', 'name' => 'Disciplina 28', 'cpp' => 'CPP-028']);
    }
}
