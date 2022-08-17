<?php

namespace App\Domain\Attendance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\ACL\Models\Role;

class ImportEmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'excel_data',
    ];
}
