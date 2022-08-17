<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EducationType;

class GetAllEducationTypes
{
    public function execute()
    {
        return EducationType::all();
    }
}
