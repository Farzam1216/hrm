<?php

namespace App\Domain\SmtpDetail\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'mail_address', 'driver', 'host', 'port', 'username', 'password', 'status'
    ];
}
