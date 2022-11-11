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
        Schema::create('qualifications', function (Blueprint $table) {
            $table->unsignedBigInteger('bond_id', false)->primary();
            $table->foreign('bond_id')->references('id')->on('bonds')->onDelete('cascade')->onUpdate('no action');
            $table->string('knowledge_area');
            $table->string('course_name');
            $table->string('institution_name');
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
        Schema::dropIfExists('qualifications');
    }
};
