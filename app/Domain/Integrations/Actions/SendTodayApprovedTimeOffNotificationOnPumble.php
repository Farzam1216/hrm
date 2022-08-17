<?php

namespace App\Domain\Integrations\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookServer\WebhookCall;
use App\Domain\Employee\Models\Employee;

class SendTodayApprovedTimeOffNotificationOnPumble
{
    public function execute($message)
    {
        return WebhookCall::create()
        ->url('https://api.pumble.com/workspaces/61af047cba152428be7e7554/incomingWebhooks/postMessage/61e528b255bcb178763b7976/tTCuwzprKASUXKZE9uixluIv')
        ->payload(['text' => $message])
        ->useSecret('sign-using-this-secret')
        ->dispatch();
    }
}
