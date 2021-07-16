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
            $table->string('cpf', 11)->unique();
            $table->string('name', 50);
            $table->string('job', 50);
            $table->foreignId('gender_id')->constrained('genders');
            $table->date('birthday');
            $table->foreignId('birth_state_id')->constrained('states');
            $table->string('birth_city', 50);
            $table->string('id_number', 15);
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->date('id_issue_date');
            $table->string('id_issue_agency', 10);
            $table->foreignId('marital_status_id')->constrained('marital_statuses');
            $table->string('spouse_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('mother_name', 50);
            $table->string('address_street', 50);
            $table->string('address_complement', 50)->nullable();
            $table->string('address_number', 5)->nullable();
            $table->string('address_district', 50)->nullable();
            $table->string('address_postal_code', 8);
            $table->foreignId('address_state_id')->constrained('states');
            $table->string('address_city', 50);
            $table->string('area_code', 3);
            $table->string('phone', 12);
            $table->string('mobile', 12);
            $table->string('email', 50)->unique();
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
