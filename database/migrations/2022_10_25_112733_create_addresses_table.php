<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id', false)->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('no action');
            $table->string('street', 50);
            $table->string('complement', 50);
            $table->string('number', 5);
            $table->string('district', 50);
            $table->string('zip_code', 8);
            $table->string('state', 2);
            $table->string('city', 50);
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
        Schema::dropIfExists('addresses');
    }
}
