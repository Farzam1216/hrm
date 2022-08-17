<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequester extends Model
{
    protected $fillable = [
        'approval_id', 'approval_requester_data','advance_approval_option_id'
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
}
