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
            'role_id' => 1,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'administrador@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 2,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'coordenador@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 3,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
            'email' => 'uab@ufes.br',
            'password' => Hash::make('senha123'),
            'role_id' => 4,
            'active' => true,
        ]);

        DB::table($this->tableName)->insert([
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
    }
}
