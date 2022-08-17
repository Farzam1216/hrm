<?php


namespace App\Domain\Employee\Actions;


use App\Domain\Employee\Models\EducationType;

class GetEducationTypeByID
{
    public function execute($id)
    {
        return EducationType::where('id', $id)->first();
    }

}