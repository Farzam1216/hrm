<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;

class DestroyBenefitGroup
{
    public function execute($id)
    {
        $benefitGroup = BenefitGroup::find($id);
        $benefitGroup->delete();
        (new DeleteBenefitGroupEntries())->execute($id); //from other tables
    }
}
