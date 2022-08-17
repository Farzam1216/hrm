<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTimeOffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_time_off_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('request_time_off_id');
            $table->date('date');
            $table->integer('hours');

            $table->foreign('request_time_off_id')
                ->references('id')
                ->on('request_time_offs')
                ->onDelete('cascade');
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
        Schema::dropIfExists('request_time_off_details');
    }
}
