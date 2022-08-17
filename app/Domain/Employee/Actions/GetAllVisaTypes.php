<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\VisaType;

class GetAllVisaTypes
{
    public function execute()
    {
        return VisaType::all();
    }
}
