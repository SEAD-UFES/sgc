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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->string('name');
            $table->string('email', 50)->unique();
            $table->string('password', 60);
            //$table->foreignId('user_type_id')->constrained('user_types');
            $table->boolean('active')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('employee_id')->unique()->nullable()->constrained('employees');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
