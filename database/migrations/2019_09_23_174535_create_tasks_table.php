<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('assigned_to')->nullable(); //manager or employee or specific employee id
            $table->unsignedInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('task_categories')->onDelete('cascade');
            $table->integer('type');
            $table->string('due_date')->nullable();
            $table->string('period')->nullable();
            $table->boolean('assigned_for_all_employees')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('tasks');
    }
}
