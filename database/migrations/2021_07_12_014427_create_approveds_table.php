<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approveds', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 50);
            $table->string('area_code', 3);
            $table->string('phone', 11);
            $table->string('mobile', 11);
            $table->string('announcement', 8);
            $table->foreignId('course_id')->nullable()->constrained('courses');
            $table->foreignId('pole_id')->nullable()->constrained('poles');
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->foreignId('approved_state_id')->nullable()->constrained('approved_states');
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
        Schema::dropIfExists('approveds');
    }
}
