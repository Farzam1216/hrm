<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroupEmployee;
use Illuminate\Database\Eloquent\Builder;

class GetEmployeesInBenefitGroup
{
    /**
     * get employees in benefit group
     * @param $id
     * @return BenefitGroupEmployee[]|Builder[]|Collection
     */
    public function execute($id)
    {
        return  BenefitGroupEmployee::with('employees')->where(
            'benefit_group_id',
            $id
        )->get();
    }
}
