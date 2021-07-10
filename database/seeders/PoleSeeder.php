<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoleSeeder extends Seeder
{
    protected $tableName = 'poles';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert(['name' => 'Afonso Cláudio', 'description' => 'Polo de Afonso Cláudio']);
        DB::table($this->tableName)->insert(['name' => 'Alegre', 'description' => 'Polo de Alegre']);
        DB::table($this->tableName)->insert(['name' => 'Aracruz', 'description' => 'Polo de Aracruz']);
        DB::table($this->tableName)->insert(['name' => 'Baixo Guandu', 'description' => 'Polo de Baixo Guandu']);
        DB::table($this->tableName)->insert(['name' => 'Bom Jesus do Norte', 'description' => 'Polo de Bom Jesus do Norte']);
        DB::table($this->tableName)->insert(['name' => 'Cachoeiro de Itapemirim', 'description' => 'Polo de Cachoeiro de Itapemirim']);
        DB::table($this->tableName)->insert(['name' => 'Castelo', 'description' => 'Polo de Castelo']);
        DB::table($this->tableName)->insert(['name' => 'Colatina', 'description' => 'Polo de Colatina']);
        DB::table($this->tableName)->insert(['name' => 'Conceição da Barra', 'description' => 'Polo de Conceição da Barra']);
        DB::table($this->tableName)->insert(['name' => 'Domingos Martins', 'description' => 'Polo de Domingos Martins']);
        DB::table($this->tableName)->insert(['name' => 'Ecoporanga', 'description' => 'Polo de Ecoporanga']);
        DB::table($this->tableName)->insert(['name' => 'Itapemirim', 'description' => 'Polo de Itapemirim']);
        DB::table($this->tableName)->insert(['name' => 'Iúna', 'description' => 'Polo de Iúna']);
        DB::table($this->tableName)->insert(['name' => 'Linhares', 'description' => 'Polo de Linhares']);
        DB::table($this->tableName)->insert(['name' => 'Mantenópolis', 'description' => 'Polo de Mantenópolis']);
        DB::table($this->tableName)->insert(['name' => 'Mimoso do Sul', 'description' => 'Polo de Mimoso do Sul']);
        DB::table($this->tableName)->insert(['name' => 'Montanha', 'description' => 'Polo de Montanha']);
        DB::table($this->tableName)->insert(['name' => 'Nova Venécia', 'description' => 'Polo de Nova Venécia']);
        DB::table($this->tableName)->insert(['name' => 'Pinheiros', 'description' => 'Polo de Pinheiros']);
        DB::table($this->tableName)->insert(['name' => 'Piúma', 'description' => 'Polo de Piúma']);
        DB::table($this->tableName)->insert(['name' => 'Santa Leopoldina', 'description' => 'Polo de Santa Leopoldina']);
        DB::table($this->tableName)->insert(['name' => 'Santa Teresa', 'description' => 'Polo de Santa Teresa']);
        DB::table($this->tableName)->insert(['name' => 'São Mateus', 'description' => 'Polo de São Mateus']);
        DB::table($this->tableName)->insert(['name' => 'Vargem Alta', 'description' => 'Polo de Vargem Alta']);
        DB::table($this->tableName)->insert(['name' => 'Venda Nova do Imigrante', 'description' => 'Polo de Venda Nova do Imigrante']);
        DB::table($this->tableName)->insert(['name' => 'Vila Velha', 'description' => 'Polo de Vila Velha']);
        DB::table($this->tableName)->insert(['name' => 'Vitória', 'description' => 'Polo de Vitória']);
    }
}
