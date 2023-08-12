<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('cpf', 11)->unique();    // Manter obrigatório [Ticket #286434 de 02/08/2023]
            $table->string('name', 50);             // Manter obrigatório [Ticket #286434 de 02/08/2023]
            $table->string('gender', 10);           // Não deveria ser obrigatório [Ticket #286434 de 02/08/2023], porém mudança acarreta alteração no banco de dados já em produção
            $table->string('email', 50)->unique();  // Manter obrigatório [Ticket #286434 de 02/08/2023]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
