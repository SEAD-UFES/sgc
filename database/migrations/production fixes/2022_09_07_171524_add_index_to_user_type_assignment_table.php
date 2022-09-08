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
        Schema::table('user_type_assignment', function (Blueprint $table) {
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
        Schema::table('user_type_assignment', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'user_type_id', 'course_id']);
        });
    }
};
