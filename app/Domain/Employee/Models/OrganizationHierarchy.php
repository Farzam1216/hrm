<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationHierarchy extends Model
{
    protected $fillable=[
        'employee_id','line_manager_id', 'parent_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee');
    }
    
    public function lineManager()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'parent_id');
    }

    public function parentEmployee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany('App\Domain\Employee\Models\OrganizationHierarchy', 'parent_id', 'employee_id');
    }
}
