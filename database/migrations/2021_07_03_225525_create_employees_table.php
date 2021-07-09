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
            $table->string('cpf')->unique();
            $table->string('name');
            $table->string('job');
            $table->foreignId('gender_id')->constrained('genders');
            $table->date('birthday');
            $table->foreignId('birth_state_id')->constrained('states');
            $table->string('birth_city');
            $table->string('id_number');
            $table->foreignId('id_type_id')->constrained('id_types');
            $table->date('id_issue_date');
            $table->string('id_issue_agency');
            $table->foreignId('marital_status_id')->constrained('marital_statuses');
            $table->string('spouse_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name');
            $table->string('address_street');
            $table->string('address_complement')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_district')->nullable();
            $table->string('address_postal_code');
            $table->foreignId('address_state_id')->constrained('states');
            $table->string('address_city');
            $table->string('area_code');
            $table->string('phone');
            $table->string('mobile');
            $table->string('email')->unique();
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
