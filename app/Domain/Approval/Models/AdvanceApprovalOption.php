<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceApprovalOption extends Model
{
    protected $fillable = [
        'approval_id', 'advance_approval_type','approval_path'
    ];
    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
    public function advanceApprovalWorkflows()
    {
        return $this->hasMany(AdvanceApprovalWorkflow::class, 'advance_approval_option_id');
    }

    public function approvalWorkflows()
    {
        return $this->belongsToMany(ApprovalWorkflow::class, 'advance_approval_workflows', 'advance_approval_option_id', 'approval_workflow_id');
    }
}
