<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 50);
            $table->string('area_code', 3);
            $table->string('landline', 11)->nullable();
            $table->string('mobile', 11);
            $table->string('hiring_process', 8);
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('pole_id')->nullable()->constrained('poles');
            $table->foreignId('course_id')->nullable()->constrained('courses');
            $table->string('call_state', 15);
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
        Schema::dropIfExists('applicants');
    }
}
