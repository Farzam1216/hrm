<?php


namespace App\Domain\Employee\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryImportEmployeeTable
{
    public function execute()
    {
        Schema::dropIfExists('import_employees');
        Schema::create('import_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('excel_data')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('official_email')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('nin')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('current_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }
}
