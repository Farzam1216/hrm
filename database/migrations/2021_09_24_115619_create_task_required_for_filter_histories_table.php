<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskRequiredForFilterHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_required_for_filter_histories', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedInteger('id');
            $table->unsignedInteger('task_id');
            $table->string('filter_type');
            $table->integer('filter_id');
            $table->string('action');
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
        Schema::dropIfExists('task_required_for_filter_histories');
    }
}
