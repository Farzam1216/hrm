<?php

namespace App\Domain\ACL\Actions;

use Illuminate\Support\Facades\DB;

class GetAccessLevelID
{
    public function execute($accessLevelName)
    {
        return DB::table('access_levels')->where('name', $accessLevelName)->pluck('id')->first();
    }
}
