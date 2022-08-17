<?php

namespace App\Domain\PayRoll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayScheduleDate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'period_start', 'period_end', 'pay_date', 'adjustment',
    ];
}
