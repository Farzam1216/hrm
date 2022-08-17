<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_histories', function (Blueprint $table) {
            $table->increments('row_id');
            $table->unsignedInteger('id');
            $table->string('name');
            $table->string('description')->nullable();;
            $table->string('assigned_to')->nullable(); //manager or employee or specific employee id
            $table->unsignedInteger('category')->nullable();
            $table->integer('type');
            $table->string('due_date')->nullable();
            $table->string('period')->nullable();
            $table->boolean('assigned_for_all_employees')->default(1);
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
        Schema::dropIfExists('tasks_histories');
    }
}
