<?php

namespace App\Domain\SmtpDetail\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Models\Employee;
use App\Jobs\MailConfigurationUpdatedJob;
use App\Providers\MailConfigServiceProvider;
use App\Domain\SmtpDetail\Models\SmtpDetail;
use App\Domain\SmtpDetail\Actions\TestNewSmtpDetails;

class UpdateSmtpDetailStatusById
{
    public function execute($request)
    {
        $smtp_detail = SmtpDetail::find($request->smtp_id);

        if ($request->status == 'active') {
            $adminUser = Employee::role('admin')->first();
            $smtp_details = SmtpDetail::all();

            $test_smtp = (new TestNewSmtpDetails())->execute($smtp_detail, '');

            if ($test_smtp != '') {
                return $data = [
                    'error' => $test_smtp
                ];
            }

            foreach ($smtp_details as $smtp) {
                $smtp->status = 'inactive';
                $smtp->save();
            }
        }

        $smtp_detail->status = $request->status;
        $smtp_detail->save();

        if ($request->status == 'active') {
            $data = (new MailConfigServiceProvider(''))->boot();
            MailConfigurationUpdatedJob::dispatch($adminUser, Auth::user())->delay(Carbon::now()->addMinutes(1));
        }

        return $smtp_detail;
    }
}
