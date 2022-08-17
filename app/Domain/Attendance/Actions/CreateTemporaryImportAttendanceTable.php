<?php


namespace App\Domain\Attendance\Actions;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryImportAttendanceTable
{
    public function execute()
    {

        Schema::dropIfExists('import_employee_attendances');
        Schema::create('import_employee_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('excel_data')->nullable();
            $table->timestamps();
        });
    }
}
