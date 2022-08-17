<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalRequestedDataField extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'requested_field_id', 'requested_by_id', 'requested_for_id', 'requested_data', 'approval_workflow_id', 'is_approved','approval_id', 'approved_by', 'comments',
    ];

    public function approvalWorkflow()
    {
        $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    public function approvalRequestedField()
    {
        $this->belongsTo(ApprovalRequestedField::class, 'requested_field_id');
    }

    public function approvalRequestedByEmployee()
    {
        $this->belongsTo('App\Domain\Employee\Models\Employee', 'requested_by_id');
    }

    public function approvalRequestedForEmployee()
    {
        $this->belongsTo('App\Domain\Employee\Models\Employee', 'requested_for_id');
    }
}
