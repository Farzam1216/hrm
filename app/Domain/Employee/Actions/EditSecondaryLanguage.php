<?php


namespace App\Domain\Employee\Actions;

class EditSecondaryLanguage
{
    public function execute($id)
    {
        $data['secondarylanguage']=(new GetSecondaryLanguageByID())->execute($id);
        return $data;
    }
}