<?php


namespace App\Domain\Approval\Actions;

class FlatRequestedData
{
    /**
     * get all requested data into single array
     *
     * @param array $requestData
     * @return array $result
     **/
    public function execute($requestData)
    {
        $result = [];
        foreach ($requestData as $Model => $fields) {
            foreach ($fields as $dbFieldName => $valueData) {
                $result[$dbFieldName] = $valueData[0]['value'];
            }
        }
        return $result;
    }
}
