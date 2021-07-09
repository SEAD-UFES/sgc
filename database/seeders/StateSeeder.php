<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    protected $tableName = 'states';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert(['uf' => 'AC', 'name' => 'ACRE', 'ibge_uf_code' => 12]);
        DB::table($this->tableName)->insert(['uf' => 'AL', 'name' => 'ALAGOAS', 'ibge_uf_code' => 27]);
        DB::table($this->tableName)->insert(['uf' => 'AM', 'name' => 'AMAZONAS', 'ibge_uf_code' => 13]);
        DB::table($this->tableName)->insert(['uf' => 'AP', 'name' => 'AMAPÁ', 'ibge_uf_code' => 16]);
        DB::table($this->tableName)->insert(['uf' => 'BA', 'name' => 'BAHIA', 'ibge_uf_code' => 29]);
        DB::table($this->tableName)->insert(['uf' => 'CE', 'name' => 'CEARÁ', 'ibge_uf_code' => 23]);
        DB::table($this->tableName)->insert(['uf' => 'DF', 'name' => 'DISTRITO FEDERAL', 'ibge_uf_code' => 53]);
        DB::table($this->tableName)->insert(['uf' => 'ES', 'name' => 'ESPÍRITO SANTO', 'ibge_uf_code' => 32]);
        DB::table($this->tableName)->insert(['uf' => 'GO', 'name' => 'GOIÁS', 'ibge_uf_code' => 52]);
        DB::table($this->tableName)->insert(['uf' => 'MA', 'name' => 'MARANHÃO', 'ibge_uf_code' => 21]);
        DB::table($this->tableName)->insert(['uf' => 'MG', 'name' => 'MINAS GERAIS', 'ibge_uf_code' => 31]);
        DB::table($this->tableName)->insert(['uf' => 'MS', 'name' => 'MATO GROSSO DO SUL', 'ibge_uf_code' => 50]);
        DB::table($this->tableName)->insert(['uf' => 'MT', 'name' => 'MATO GROSSO', 'ibge_uf_code' => 51]);
        DB::table($this->tableName)->insert(['uf' => 'PA', 'name' => 'PARÁ', 'ibge_uf_code' => 15]);
        DB::table($this->tableName)->insert(['uf' => 'PB', 'name' => 'PARAIBA', 'ibge_uf_code' => 25]);
        DB::table($this->tableName)->insert(['uf' => 'PE', 'name' => 'PERNAMBUCO', 'ibge_uf_code' => 26]);
        DB::table($this->tableName)->insert(['uf' => 'PI', 'name' => 'PIAUÍ', 'ibge_uf_code' => 22]);
        DB::table($this->tableName)->insert(['uf' => 'PR', 'name' => 'PARANÁ', 'ibge_uf_code' => 41]);
        DB::table($this->tableName)->insert(['uf' => 'RJ', 'name' => 'RIO DE JANEIRO', 'ibge_uf_code' => 33]);
        DB::table($this->tableName)->insert(['uf' => 'RN', 'name' => 'RIO GRANDE DO NORTE', 'ibge_uf_code' => 24]);
        DB::table($this->tableName)->insert(['uf' => 'RO', 'name' => 'RONDÔNIA', 'ibge_uf_code' => 11]);
        DB::table($this->tableName)->insert(['uf' => 'RR', 'name' => 'RORAIMA', 'ibge_uf_code' => 14]);
        DB::table($this->tableName)->insert(['uf' => 'RS', 'name' => 'RIO GRANDE DO SUL', 'ibge_uf_code' => 43]);
        DB::table($this->tableName)->insert(['uf' => 'SC', 'name' => 'SANTA CATARINA', 'ibge_uf_code' => 42]);
        DB::table($this->tableName)->insert(['uf' => 'SE', 'name' => 'SERGIPE', 'ibge_uf_code' => 28]);
        DB::table($this->tableName)->insert(['uf' => 'SP', 'name' => 'SÃO PAULO', 'ibge_uf_code' => 35]);
        DB::table($this->tableName)->insert(['uf' => 'TO', 'name' => 'TOCANTINS', 'ibge_uf_code' =>  17]);
    }
}
