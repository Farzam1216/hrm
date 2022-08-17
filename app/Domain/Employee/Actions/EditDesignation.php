<?php


namespace App\Domain\Employee\Actions;

class EditDesignation
{
    public function execute($id)
    {
        $data['designation']=(new GetDesignationByID())->execute($id);
        return $data;
    }
}