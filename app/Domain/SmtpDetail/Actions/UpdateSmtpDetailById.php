<?php

namespace App\Domain\SmtpDetail\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Domain\Employee\Models\Employee;
use App\Jobs\MailConfigurationUpdatedJob;
use App\Providers\MailConfigServiceProvider;
use App\Domain\SmtpDetail\Models\SmtpDetail;
use App\Domain\SmtpDetail\Actions\TestNewSmtpDetails;

class UpdateSmtpDetailById
{
    public function execute($request, $id)
    {
        $test_smtp = (new TestNewSmtpDetails())->execute($request, $request->password);

        if ($test_smtp != '') {
            return $data = [
                'error' => $test_smtp
            ];
        }
        
        if ($request->status == 'active') {
            $adminUser = Employee::role('admin')->first();
            $smtp_details = SmtpDetail::all();

            foreach ($smtp_details as $smtp) {
                $smtp->status = 'inactive';
                $smtp->save();
            }
        }

        $smtp_detail = SmtpDetail::find($id);
        $smtp_detail->name = $request->name;
        $smtp_detail->mail_address = $request->mail_address;
        $smtp_detail->driver = $request->driver;
        $smtp_detail->host = $request->host;
        $smtp_detail->port = $request->port;
        $smtp_detail->username = $request->username;
        $smtp_detail->password = Crypt::encryptString($request->password);
        $smtp_detail->status = $request->status;
        $smtp_detail->save();

        if ($request->status == 'active') {
            $data = (new MailConfigServiceProvider(''))->boot();
            MailConfigurationUpdatedJob::dispatch($adminUser, Auth::user())->delay(Carbon::now()->addMinutes(1));
        }

        return $smtp_detail;
    }
}
