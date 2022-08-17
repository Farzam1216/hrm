<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequestedField extends Model
{
    protected $fillable = [
        'approval_id', 'form_fields'
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }
}
