<?php


namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\Compensation;

class UpdateCompensationById
{
    public function execute($request)
    {
        $compensation = Compensation::find($request->compensation_id);
        $compensation->effective_date = $request->effective_date;
        $compensation->pay_schedule_id = $request->pay_schedule_id;
        $compensation->pay_type = $request->pay_type;
        $compensation->pay_rate = $request->pay_rate;
        $compensation->pay_rate_frequency = $request->pay_rate_frequency;
        $compensation->overtime_status = $request->overtime_status;
        $compensation->change_reason_id = $request->change_reason_id;
        $compensation->comment = $request->comment;
        $compensation->save();
        
        return $compensation;
    }
}
