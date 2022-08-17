<?php


namespace App\Domain\Employee\Actions;


use App\Domain\Employee\Models\VisaType;

class GetVisaTypeByID
{
    public function execute($id)
    {
        return VisaType::where('id', $id)->first();
    }
}