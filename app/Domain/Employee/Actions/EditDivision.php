<?php


namespace App\Domain\Employee\Actions;

class EditDivision
{
    public function execute($id)
    {
        $data['division']=(new GetDivisionByID())->execute($id);
        return $data;
    }
}