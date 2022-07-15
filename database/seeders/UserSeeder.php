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
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 1,
            'active' => true,
            'employee_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'diretor@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 2,
            'active' => true,
            'employee_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'uab@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 3,
            'active' => true,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'secretario@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 4,
            'active' => true,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'ldi@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 5,
            'active' => true,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'ldi_inativo@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 5,
            'active' => false,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'coordenador01@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 6,
            'active' => true,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'comum01@ufes.br',
            'password' => Hash::make('changeme'),
            // 'user_type_id' => 6,
            'active' => true,
            'employee_id' => null,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'assistente01@ufes.br',
            'password' => Hash::make('changeme'),
            'active' => true,
            'employee_id' => null,
        ]);
    }
}
