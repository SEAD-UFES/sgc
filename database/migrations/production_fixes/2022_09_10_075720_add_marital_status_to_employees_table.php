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
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['marital_status_id']);
            $table->dropColumn(['marital_status_id']);
            $table->string('marital_status', 20)->nullable()->after('id_issue_agency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('marital_status');
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses');
        });
    }
};
