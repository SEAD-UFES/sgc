<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    protected $tableName = 'roles';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de curso I',
            'description' => 'Coordenador de curso, com 3 anos de experiência em docência no ensino superior',
            'grant_value' => 1400,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de curso II',
            'description' => 'Coordenador de curso, com 1 ano de experiência em docência no ensino superior',
            'grant_value' => 1100,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Tutor a Distancia',
            'description' => 'Tutor a Distancia',
            'grant_value' => 765,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Tutor Presencial',
            'description' => 'Tutor Presencial',
            'grant_value' => 765,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor I',
            'description' => 'Professor, com 3 anos de experiência em docência no ensino superior',
            'grant_value' => 1300,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor II',
            'description' => 'Professor, com 1 ano de experiência em docência no ensino superior',
            'grant_value' => 1100,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de Tutoria I',
            'description' => 'Coordenador de Tutoria, com 3 anos de experiência em docência no ensino superior',
            'grant_value' => 1300,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de Tutoria II',
            'description' => 'Coordenador de Tutoria, com 1 ano de experiência em docência no ensino superior',
            'grant_value' => 1100,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor orientador de TCC I',
            'description' => 'Professor orientador de TCC, com 3 anos de experiência em docência no ensino superior',
            'grant_value' => 1300,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor orientador de TCC II',
            'description' => 'Professor orientador de TCC, com 1 ano de experiência em docência no ensino superior',
            'grant_value' => 1100,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor formador de componentes curriculares I',
            'description' => 'Professor formador de componentes curriculares, com 3 anos de experiência em docência no ensino superior',
            'grant_value' => 1300,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor formador de componentes curriculares II',
            'description' => 'Professor formador de componentes curriculares, com 1 ano de experiência em docência no ensino superior',
            'grant_value' => 1100,
            'grant_type_id' => 1,
        ]);
    }
}
