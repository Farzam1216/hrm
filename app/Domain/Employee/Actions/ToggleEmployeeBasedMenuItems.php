<?php


namespace App\Domain\Employee\Actions;

use Illuminate\Support\Facades\Auth;

class ToggleEmployeeBasedMenuItems
{
    public function execute($id)
    {
        if ($id != Auth::id()) {
            session(['unauthorized_user' => (new GetEmployeeByID())->execute($id)]);
        } else {
            session()->forget('unauthorized_user');
        }
    }
}
