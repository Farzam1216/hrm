<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'approval_type_id',
        'name',
        'description',
        'status',
    ];

    public function approvalType()
    {
        return $this->belongsTo(ApprovalType::class, 'approval_type_id');
    }

    public function approvalWorkflow()
    {
        return $this->hasMany(ApprovalWorkflow::class, 'approval_id');
    }

    public function approvalRequestedFields()
    {
        return $this->hasMany(ApprovalRequestedField::class, 'approval_id');
    }

    public function approvalRequesters()
    {
        return $this->hasMany(ApprovalRequester::class, 'approval_id');
    }

    public function advanceApprovalOptions()
    {
        return $this->hasMany(AdvanceApprovalOption::class, 'approval_id');
    }
}
