<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTimeOffNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_time_off_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_time_off_id');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('request_time_off_id')->references('id')->on('request_time_offs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_time_off_notifications');
    }
}
