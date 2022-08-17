<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\RedirectsUsers;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\PasswordReset as PasswordResetModel;
use App\Http\Controllers\Auth\LoginController;
use Session;
use App;

class ResetController extends \App\Http\Controllers\Controller
{
    use RedirectsUsers;

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        $request->validate([
            'token' => 'required',
            'official_email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $reset_token = PasswordResetModel::where('email', $request->official_email)->first();
        if ($reset_token) {
            $validateToken = Hash::check($request->token, $reset_token->token);
            if ($validateToken == true) {
                $employee = Employee::where('official_email', $reset_token->email)->first();

                // Redirect the employee back if the email is invalid
                if (!$employee) {
                    return redirect()->back()->withErrors(['email' => 'Email not found']);
                }

                $employee->password = \Hash::make($request->password);
                $employee->update();

                if ($employee) {
                    PasswordResetModel::where('email', $reset_token->email)->delete();

                    $this->guard()->login($employee);

                    if (Auth::user()->isAdmin() || Auth::user()->status == 1) {
                        Session::flash('success', trans('Password is reset successfully'));
                        return redirect($locale.'/dashboard'); 
                    }

                    if (Auth::user()->status == 0) {
                        $logout = new LoginController();
                        $logout->logout($request);
                        Session::flash('success', trans('Password is reset successfully'));
                        return redirect()->route('login'); 
                    }
                }
            } else {
                Session::flash('error', trans('Password reset token is invalid'));
                return redirect()->back();
            }
        } else {
            Session::flash('error', trans('Password reset token is not available'));
            return redirect('/password/reset');
        }
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
