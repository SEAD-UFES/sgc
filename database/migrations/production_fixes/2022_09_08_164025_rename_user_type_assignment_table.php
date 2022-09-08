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
        Schema::table('user_type_assignments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['course_id']);
            $table->dropUnique(['user_id', 'user_type_id', 'course_id']);
        });

        Schema::rename('user_type_assignments', 'responsibilities');
        
        Schema::table('responsibilities', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unique(['user_id', 'user_type_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsibilities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['course_id']);
            $table->dropUnique(['user_id', 'user_type_id', 'course_id']);
        });

        Schema::rename('responsibilities', 'user_type_assignments');

        Schema::table('user_type_assignments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unique(['user_id', 'user_type_id', 'course_id']);
        });
    }
};
