<?php

namespace App\Domain\Approval\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approval_type_id');
    }
}
