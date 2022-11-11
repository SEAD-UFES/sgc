<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBondPoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bond_pole', function (Blueprint $table) {
            $table->unsignedBigInteger('bond_id', false)->primary();
            $table->foreign('bond_id')->references('id')->on('bonds')->onDelete('cascade')->onUpdate('no action');
            $table->foreignId('pole_id')->constrained('poles')->onDelete('cascade')->onUpdate('no action');
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
        Schema::dropIfExists('bond_pole');
    }
}
