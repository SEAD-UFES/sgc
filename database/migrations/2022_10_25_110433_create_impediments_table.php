<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpedimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impediments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bond_id')->constrained('bonds')->cascadeOnDelete();
            $table->string('description')->default('Vínculo ainda não revisado');
            $table->foreignId('reviewer_id')->constrained('users');
            $table->timestamp('reviewed_at');
            $table->foreignId('closed_by_id')->nullable()->constrained('users');
            $table->timestamp('closed_at')->nullable();
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
        Schema::dropIfExists('impediments');
    }
}
