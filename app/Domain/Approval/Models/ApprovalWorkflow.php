<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalWorkflow extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'approval_id',
        'approval_hierarchy',
        'level_number',
        'advance_approval_option_id'
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }

    public function approvalRequestedDataFields()
    {
        return $this->hasMany(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function advanceApprovalWorkflows()
    {
        return  $this->hasMany(AdvanceApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function advanceApprovalOptions()
    {
        return $this->belongsToMany(AdvanceApprovalOption::class, 'advance_approval_workflows', 'approval_workflow_id', 'advance_approval_option_id');
    }
    public function requestedData()
    {
        return $this->hasMany(ApprovalRequestedDataField::class, 'approval_workflow_id');
    }
}
