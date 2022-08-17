<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;

class DeleteEmployeeBenefits
{
    /**
     * delete employee benefits for group
     * @param $data
     */
    public function execute($data)
    {
        //delete previous employee benefits
        $deleteEmployeeBenefit = EmployeeBenefit::where('employee_id', $data->employees_Id)->get();
        foreach ($deleteEmployeeBenefit as  $deleteBenefits) {
            $deleteBenefits->delete();
        }
    }
}
