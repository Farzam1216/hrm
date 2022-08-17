<?php

namespace App\Domain\TimeOff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestTimeOffNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_time_off_id', 'status',
    ];
}
