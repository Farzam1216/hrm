<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\SecondaryLanguage as SecondaryLanguage;

class GetSecondaryLanguageByID
{
    public function execute($id)
    {
        return SecondaryLanguage::find($id);
    }
}