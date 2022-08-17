<?php


namespace App\Domain\Approval\Actions;

class StoreApproval
{
    /**
     * @param $request
     */
    public function execute($request)
    {
        $fields = $request->all();
        array_shift($fields);
        array_shift($fields);
        $groupedData = (new GetFieldCategories())->execute($fields);
        $groupedData['Approval'] = ['name' => $request->name, 'description' => $request->description];
        (new StoreApprovalWithRequestedFields())->execute($groupedData);
    }
}
