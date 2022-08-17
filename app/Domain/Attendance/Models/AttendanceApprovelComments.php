<?php

namespace App\Domain\Attendance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceApprovelComments extends Model
{
    protected $fillable = [
        'approval_id',
        'comment',
    ];

    public function approval()
    {
        return $this->belongsTo(AttendanceApproval::class, 'approval_id');
    }
}
