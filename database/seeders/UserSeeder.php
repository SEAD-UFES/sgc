<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    protected $tableName = 'users';

    public function run()
    {
        DB::table($this->tableName)->insert([
            'email' => 'admin@ufes.br',
            'password' => Hash::make('senha123'),
            'user_type_id' => 1,
            'active' => true,
            'employee_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'diretor@ufes.br',
            'password' => Hash::make('senha123'),
            'user_type_id' => 2,
            'active' => true,
            'employee_id' => 2,
        ]);

        /* DB::table($this->tableName)->insert([
            'email' => 'coordenador@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 3,
            'active' => true,
        ]); */

        DB::table($this->tableName)->insert([
            'email' => 'uab@ufes.br',
            'password' => Hash::make('senha123'),
            'user_type_id' => 3,
            'active' => true,
            'employee_id' => null,
        ]);

        /* DB::table($this->tableName)->insert([
            'email' => 'tutord@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 5,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'tutorp@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 6,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'prof1@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 7,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'prof2@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 8,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'coordtut@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 9,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'proftcc@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 10,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'profform@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 11,
            'active' => true,
        ]); */

        DB::table($this->tableName)->insert([
            'email' => 'secretario@ufes.br',
            'password' => Hash::make('senha123'),
            'user_type_id' => 4,
            'active' => true,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'ldi@ufes.br',
            'password' => Hash::make('senha123'),
            'user_type_id' => 5,
            'active' => true,
            'employee_id' => null,
        ]);
    }
}
