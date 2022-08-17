<?php

namespace App\Domain\Compensation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensationChangeReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'change_occur',
    ];
}
