<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identities', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id', false)->primary();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('no action');
            $table->foreignId('type_id')->constrained('document_types');
            $table->string('number', 15);
            $table->date('issue_date');
            $table->string('issuer', 10);
            $table->string('issuer_state', 2);
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
        Schema::dropIfExists('identities');
    }
}
