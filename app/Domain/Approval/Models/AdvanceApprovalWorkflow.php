<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceApprovalWorkflow extends Model
{
    protected $fillable = [
        'approval_workflow_id', 'advance_approval_option_id'
    ];
    public function approvalWorkflow()
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }
    public function advanceApprovalOption()
    {
        return $this->belongsTo(AdvanceApprovalOption::class, 'advance_approval_option_id');
    }
}
