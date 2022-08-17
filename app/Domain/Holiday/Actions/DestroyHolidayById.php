<?php


namespace App\Domain\Holiday\Actions;

use App\Domain\Holiday\Models\Holiday;

class DestroyHolidayById
{

    /**
     * @return mixed
     */
    public function execute($id)
    {
        $holiday = Holiday::find($id);

        if ($holiday) {
            $holiday->destroy($id);
        }
        
        return $holiday;
    }
}
