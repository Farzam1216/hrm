<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceApprovalOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_approval_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('approval_id');
            $table->foreign('approval_id')->references('id')->on('approvals')->onDelete('cascade');
            $table->string('advance_approval_type');
            $table->json('approval_path')->nullable();
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
        Schema::dropIfExists('advance_approval_options');
    }
}
