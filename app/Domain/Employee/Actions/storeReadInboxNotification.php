<?php
namespace App\Domain\Employee\Actions;
use App\Models\Notification;
use DateTime;
class storeReadInboxNotification
{
    public function execute($request)
    {
        $data = $request->all();
        if($data['readStatus'] == null){
            $inbox = Notification::find($data['notificationID']);
            $inbox->read_at = new DateTime();
            $inbox->save();
            return response()->json($inbox);
        }
       
    }
}
