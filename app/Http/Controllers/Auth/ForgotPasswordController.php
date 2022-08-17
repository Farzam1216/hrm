<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\Mail\PasswordResetLink;
use App\Domain\Employee\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Passwords\PasswordBroker;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function SendsPasswordResetLink(Request $request)
    {
        $request->validate([
            'official_email' => 'required|email'
        ]);
        $employee = Employee::where('official_email', $request->official_email)->first();
        if ($employee) {
            $employee->email = $employee->official_email;
            $token = app(PasswordBroker::class)->createToken($employee);
            Mail::to($employee->email)->send(new PasswordResetLink($employee, $token));
            Session::flash('success', trans('We have e-mailed you password reset link!'));
            return redirect('/password/reset');
        } else {
            Session::flash('error', trans('Employee not exist against entered email'));
            return back()->withInput($request->only('official_email'));
        }
    }
}
