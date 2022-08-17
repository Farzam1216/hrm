<?php


namespace App\Domain\Employee\Actions;

use App\Mail\CompanyPoliciesMail;
use App\Mail\EmailPasswordChange;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Session;

class SendAccountDetailsToNewEmployee
{
    public function execute($employee_id, $request, $locale)
    {
        $when = Carbon::now()->addMinutes(10);
        //send message for password information and change password.
        try {
            Mail::to($request->official_email)->later($when, new EmailPasswordChange($employee_id, $locale));
            Mail::to($request->personal_email)->later($when, new EmailPasswordChange($employee_id, $locale));

            //policies
            Mail::to($request->official_email)->later($when, new CompanyPoliciesMail($locale));
        } catch (\Exception $e) {
            Session::flash('error', trans('language.Email Not Send Please Set Email Configuration In .env File'));
        }
    }
}
