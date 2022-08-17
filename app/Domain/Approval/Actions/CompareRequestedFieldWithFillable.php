<?php


namespace App\Domain\Approval\Actions;

class CompareRequestedFieldWithFillable
{
    /**
     * @param array
     * @param array fillables
     *
     * @return array
     */
    public function execute($updateArray, $data)
    {
        $requiredData = [];
        foreach ($updateArray as $key => $value) {
            foreach ($data as $fillable) {
                if ($fillable == $key) {
                    $requiredData = array_add($requiredData, $key, $value);
                }
            }
        }
        return $requiredData;
    }
}
