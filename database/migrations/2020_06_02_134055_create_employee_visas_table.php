<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeVisasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_visas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('visa_type_id');
            $table->foreign('visa_type_id')
                ->references('id')->on('visa_types')
                ->onDelete('cascade');
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('cascade');
            $table->date('issue_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('employee_visas');
    }
}
