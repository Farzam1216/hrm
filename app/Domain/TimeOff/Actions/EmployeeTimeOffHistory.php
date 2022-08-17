<?php


namespace App\Domain\TimeOff\Actions;

class EmployeeTimeOffHistory
{
    /**
     * @param $req
     * @param $id
     * @return mixed
     */
    public function execute($req, $id)
    {
        $typeId = $req->typeid;
        $getyear = $req->year;
        $earnedandrequestfilter = $req->earnedandrequestfilter;
        //Conditions for Request Option
        if ($typeId != "" && $getyear != "" && $earnedandrequestfilter == "Requests") {
            $request = new GetRequestedHistoryByTypeAndYear();
            return $request->execute($typeId, $getyear, $id);
        } elseif ($typeId != "" && $earnedandrequestfilter == "Requests") {
            $request = new GetRequestedHistoryByType();
            return $request->execute($typeId, $id);
        } elseif ($getyear != "" && $earnedandrequestfilter == "Requests") {
            $request = new GetRequestedHistoryByYear();
            return $request->execute($getyear, $id);
        } elseif ($earnedandrequestfilter == "Requests") {
            $request = new GetRequestedHistoryForSpecificEmployee();
            return $request->execute($id);
        }
        //Conditions for Request Option Ends
        //Conditions for Earned/Used Option
        if ($typeId != "" && $getyear != "" && $earnedandrequestfilter == "Earned/Used") {
            $transaction =new GetEarnedOrUsedTransactionsByTypeAndYear();
            return $transaction->execute($typeId, $getyear, $id);
        } elseif ($typeId != "" && $earnedandrequestfilter == "Earned/Used") {
            $transaction = new GetEarnedOrUsedTransactionsByType();
            return $transaction->execute($typeId, $id);
        } elseif ($getyear != "" && $earnedandrequestfilter == "Earned/Used") {
            $transaction = new GetEarnedOrUsedTransactionsByYear();
            return $transaction->execute($getyear, $id);
        } else {
            $transaction = new GetEarnedOrUsedTransactionsForSpecificEmployee();
            return $transaction->execute($id);
        }
        //Conditions for Earned/Used Option Ends
    }
}
