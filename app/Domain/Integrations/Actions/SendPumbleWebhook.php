<?php


namespace App\Domain\Integrations\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookServer\WebhookCall;
use App\Domain\Employee\Models\Employee;

class SendPumbleWebhook
{
    public function execute($attendanceStatus)
    {
//         return WebhookCall::create()
//         ->url('https://api.pumble.com/workspaces/610873c5e17a864d0764cea4/incomingWebhooks/postMessage/6119c6b93247de6dd4efaa3f/DVeQN2bZtUAmpF7F7WeVHy6U')
// //        ->url('https://api.pumble.com/workspaces/619b9da2f2b2305fe026e1d0/incomingWebhooks/postMessage/619b9da2f2b2305fe026e1d2/dnvh29esk4sluQIk3MEs3bRg')
//         ->payload(['text' => $attendanceStatus])
//         ->useSecret('sign-using-this-secret')
//         ->dispatch();
    }
}
