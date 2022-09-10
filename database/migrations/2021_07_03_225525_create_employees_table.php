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
            $table->string('job', 50)->nullable();                                                 //Relaxed (nullable)
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();                                                  //Relaxed (nullable)
            $table->foreignId('birth_state_id')->nullable()->constrained('states');                //Relaxed (nullable)
            $table->string('birth_city', 50)->nullable();                                          //Relaxed (nullable)
            $table->string('id_number', 15)->nullable();                                           //Relaxed (nullable)
            $table->foreignId('document_type_id')->nullable()->constrained('document_types');      //Relaxed (nullable)
            $table->date('id_issue_date')->nullable();                                             //Relaxed (nullable)
            $table->string('id_issue_agency', 10)->nullable();                                     //Relaxed (nullable)
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses');   //Relaxed (nullable)
            $table->string('spouse_name', 50)->nullable();
            $table->string('father_name', 50)->nullable();
            $table->string('mother_name', 50)->nullable();                                         //Relaxed (nullable)
            $table->string('address_street', 50)->nullable();                                      //Relaxed (nullable)
            $table->string('address_complement', 50)->nullable();
            $table->string('address_number', 5)->nullable();
            $table->string('address_district', 50)->nullable();
            $table->string('address_postal_code', 8)->nullable();                                  //Relaxed (nullable)
            $table->foreignId('address_state_id')->nullable()->constrained('states');              //Relaxed (nullable)
            $table->string('address_city', 50)->nullable();                                        //Relaxed (nullable)
            $table->string('area_code', 3)->nullable();                                            //Relaxed (nullable)
            $table->string('phone', 12)->nullable();                                               //Relaxed (nullable)
            $table->string('mobile', 12)->nullable();                                              //Relaxed (nullable)
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
