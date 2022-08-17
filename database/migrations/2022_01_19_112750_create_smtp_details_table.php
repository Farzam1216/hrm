<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmtpDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smtp_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mail_address');
            $table->string('driver');
            $table->string('host');
            $table->string('port');
            $table->string('username');
            $table->text('password');
            $table->string('status');
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
        Schema::dropIfExists('smtp_details');
    }
}
