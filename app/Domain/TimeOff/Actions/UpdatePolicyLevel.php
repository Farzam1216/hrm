<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\PolicyLevel;

class UpdatePolicyLevel
{
    /**
     * @param $policylevel
     * @param $id
     */
    public function execute($policylevel, $id)
    {
        $level = new PolicyLevel();
        $level->policy_id = $id;
        $level->level_start_status = $policylevel["accrual-start"] . $policylevel["start-type"];
        $result = [];
        if (isset($policylevel['accrual-hours'])) {
            $accrualHours = intval($policylevel['accrual-hours']);
            $result["accrual-hours"] = $accrualHours;
        } else {
            $result["accrual-hours"] = null;
        }
        if ($policylevel["accrual-type"] == "Daily") {
            $result["accrual-type"] = "Daily";
        } elseif ($policylevel["accrual-type"] == "Weekly") {
            $result["accrual-type"] = "Weekly";
            $result["accrual-type"] = $policylevel["accrual-type"];
            $result["first-accrual-day"] = $policylevel["first-accrual-day"];
        } elseif ($policylevel["accrual-type"] == "Every other week") {
            $result["accrual-type"] = "Every other week";
            $result["accrual-day"] = $policylevel["accrual-day"];
            $result["accrual-week"] = $policylevel["accrual-week"];
        } elseif ($policylevel["accrual-type"] == "Twice a month") {
            $result["accrual-type"] = "Twice a month";
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-lastdate"] = $policylevel["accrual-lastdate"];
        } elseif ($policylevel["accrual-type"] == "Monthly") {
            $result["accrual-type"] = "Monthly";
            $result["accrual-firstdatemonthly"] = $policylevel["accrual-firstdatemonthly"];
        } elseif ($policylevel["accrual-type"] == "Twice a Yearly") {
            $result["accrual-type"] = "Twice a Yearly";
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-firstMonth"] = $policylevel["accrual-firstMonth"];
            $result["accrual-seconddate"] = $policylevel["accrual-seconddate"];
            $result["accrual-secondMonth"] = $policylevel["accrual-secondMonth"];
        } elseif ($policylevel["accrual-type"] == "Quarterly") {
            $result["accrual-type"] = "Quarterly";
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-firstMonth"] = $policylevel["accrual-firstMonth"];
            $result["accrual-seconddate"] = $policylevel["accrual-seconddate"];
            $result["accrual-secondMonth"] = $policylevel["accrual-secondMonth"];
            $result["accrual-thirddate"] = $policylevel["accrual-thirddate"];
            $result["accrual-thirdMonth"] = $policylevel["accrual-thirdMonth"];
            $result["accrual-fourthdate"] = $policylevel["accrual-fourthdate"];
            $result["accrual-fourthMonth"] = $policylevel["accrual-fourthMonth"];
        } elseif ($policylevel["accrual-type"] == "Yearly") {
            $result["accrual-type"] = "Yearly";
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-firstMonth"] = $policylevel["accrual-firstMonth"];
        } elseif ($policylevel["accrual-type"] == "Anniversary") {
            $result["accrual-type"] = "Anniversary";
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Daily") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Weekly") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["first-accrual-day"] = $policylevel["first-accrual-day"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Every Other Week") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["first-accrual-day"] = $policylevel["first-accrual-day"];
            $result["accrual-week"] = $policylevel["accrual-week"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Monthly") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["accrual-firstdatemonthly"] = $policylevel["accrual-firstdatemonthly"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Twice a month") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-lastdate"] = $policylevel["accrual-lastdate"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Twice a Yearly") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-firstMonth"] = $policylevel["accrual-firstMonth"];
            $result["accrual-seconddate"] = $policylevel["accrual-seconddate"];
            $result["accrual-secondMonth"] = $policylevel["accrual-secondMonth"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Quarterly") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-firstMonth"] = $policylevel["accrual-firstMonth"];
            $result["accrual-seconddate"] = $policylevel["accrual-seconddate"];
            $result["accrual-secondMonth"] = $policylevel["accrual-secondMonth"];
            $result["accrual-thirddate"] = $policylevel["accrual-thirddate"];
            $result["accrual-thirdMonth"] = $policylevel["accrual-thirdMonth"];
            $result["accrual-fourthdate"] = $policylevel["accrual-fourthdate"];
            $result["accrual-fourthMonth"] = $policylevel["accrual-fourthMonth"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Yearly") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
            $result["accrual-firstdate"] = $policylevel["accrual-firstdate"];
            $result["accrual-firstMonth"] = $policylevel["accrual-firstMonth"];
        } elseif ($policylevel["accrual-type"] == "Per hour worked" && $policylevel["Accrual-Frequency"] == "Anniversary") {
            $result["accrual-type"] = "Per hour worked";
            $result["Accrual-Frequency"] = $policylevel["Accrual-Frequency"];
        }

        $level->amount_accrued = json_encode($result);
        if (isset($policylevel["max-accrual"])) {
            $level->max_accrual = $policylevel["max-accrual"];
        }
        //Carryover types eg. None,upto,unlimited
        if ($policylevel['carryover-type'] == "0") {
            $level->carry_over_amount = 0;
        } elseif ($policylevel['carryover-type'] == "1") {
            if (isset($policylevel["carry-upto"])) {
                $level->carry_over_amount = $policylevel["carry-upto"];
            }
        } elseif ($policylevel['carryover-type'] == "2") {
            $level->carry_over_amount = 'unlimited'; //test
        }
        $level->save();
    }
}
