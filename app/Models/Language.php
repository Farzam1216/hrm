<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $table = 'languages';
    
    protected $fillable = [
        'name', 'short_name', 'status',
    ];
}
