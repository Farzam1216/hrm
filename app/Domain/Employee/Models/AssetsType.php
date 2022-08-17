<?php

namespace App\Domain\Employee\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsType extends Model
{
    protected $fillable = [
        'name', 'status',
    ];
    public function assets()
    {
        return $this->hasMany('App\Domain\Employee\Models\Asset', 'asset_category');
    }
}
