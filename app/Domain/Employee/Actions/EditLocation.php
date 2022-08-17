<?php


namespace App\Domain\Employee\Actions;

class EditLocation
{
    public function execute($id)
    {
        $data['location']=(new GetLocationByID())->execute($id);
        $data['countries'] = (new GetAllCountries())->execute();
        return $data;
    }
}
