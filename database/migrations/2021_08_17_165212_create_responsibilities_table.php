<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateResponsibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('user_type_id')->constrained('user_types');
            $table->foreignId('course_id')->nullable()->constrained('courses');
            $table->date('begin')->default(Carbon::now());
            $table->date('end')->nullable();
            $table->unique(['user_id', 'user_type_id', 'course_id']);
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
        Schema::dropIfExists('responsibilities');
    }
}
