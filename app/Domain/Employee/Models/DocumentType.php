<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable=[
        'doc_type_name','status'
    ];

    public function employeeDocuments()
    {
        return $this->hasMany('App\Domain\Employee\Models\EmployeeDocument', 'doc_type');
    }
}
