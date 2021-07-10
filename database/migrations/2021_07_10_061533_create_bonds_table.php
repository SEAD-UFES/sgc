<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('pole_id')->constrained('poles');
            //$table->foreignId('classroom_id')->constrained('classrooms');
            $table->date('begin')->nullable();
            $table->date('end')->nullable();
            $table->date('terminated_on')->nullable();
            $table->boolean('volunteer')->default(false);
            $table->boolean('impediment')->default(false);
            $table->date('uaba_checked_on')->nullable();
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
        Schema::dropIfExists('bonds');
    }
}
