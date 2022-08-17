<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SaveLoginTime
{
    //LastLogin
    public function execute()
    {
        $time = Employee::find(Auth::user()->id);
        $time->last_login = Carbon::now()->toDateTimeString();
        $time->save();
    }
}
