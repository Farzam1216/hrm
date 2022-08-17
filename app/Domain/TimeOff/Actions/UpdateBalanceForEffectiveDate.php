<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffTransaction;
use Illuminate\Support\Facades\DB;

class UpdateBalanceForEffectiveDate
{
    /**
     * @param $assign_id
     * @param $data
     */
    public function execute($assign_id, $data)
    {
        //next entries after effective date for updating balance
        $nextEntries = DB::table('time_off_transactions')
            ->where('assign_time_off_id', $assign_id)
            ->where('accrual_date', '=>', $data['effective-date'])
            ->orderBy('accrual_date', 'asc')
            ->get();

        foreach ($nextEntries as $entry) {
            $updateBalance = TimeOffTransaction::find($entry->id);
            if ($data['amount-options'] == "Subtract") {
                $entry->balance = $entry->balance - $data['adjust-hours'];
            } else {
                $entry->balance = $entry->balance + $data['adjust-hours'];
            }
            $updateBalance->balance = $entry->balance;
            $updateBalance->save();
        }
    }
}
