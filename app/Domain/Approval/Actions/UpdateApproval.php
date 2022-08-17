<?php


namespace App\Domain\Approval\Actions;

class UpdateApproval
{
    /**
     * @param $id
     * @return mixed $data
     */
    public function execute($lang, $request, $id)
    {
        $fields = $request->all();
        array_shift($fields);
        array_shift($fields);
        $groupedData = (new GetFieldCategories())->execute($fields);
        $groupedData['Approval'] = ['name' => $request->name, 'description' => $request->description];
        (new UpdateRequestedFields())->execute($id, $groupedData);
    }
}
