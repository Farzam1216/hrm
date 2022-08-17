<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name', 'url',  'status', 'created_at','updated_at'
    ];
}
