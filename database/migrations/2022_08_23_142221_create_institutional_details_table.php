<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutional_details', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id', false)->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('no action');
            $table->string('login')->unique();
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
        Schema::dropIfExists('institutional_details');
    }
};
