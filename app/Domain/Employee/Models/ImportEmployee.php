<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\ACL\Models\Role;

class ImportEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'excel_data',
        'employee_no',
        'firstname',
        'lastname',
        'contact_no',
        'official_email',
        'personal_email',
        'nin',
        'gender',
        'marital_status',
        'emergency_contact_relationship',
        'emergency_contact',
        'current_address',
        'permanent_address',
        'city',
    ];
}
