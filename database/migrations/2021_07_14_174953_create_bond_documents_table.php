<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBondDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bond_documents', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->timestamps();
        });
        
        DB::statement("ALTER TABLE bond_documents ADD file_data MEDIUMBLOB");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bond_documents');
    }
}
