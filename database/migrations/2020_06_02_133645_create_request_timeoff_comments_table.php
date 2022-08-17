<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTimeoffCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_timeoff_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('request_time_off_id');
            $table->string('comment');
            $table->unsignedInteger('commented_by');

            $table->foreign('request_time_off_id')
                ->references('id')
                ->on('request_time_offs')
                ->onDelete('cascade');
            $table->foreign('commented_by')
                ->references('id')
                ->on('employees')
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
        Schema::dropIfExists('request_timeoff_comments');
    }
}
