<?php

namespace App\Http\Controllers\Auth;

use App;
use App\Domain\Employee\Actions\SaveLoginTime;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
            |--------------------------------------------------------------------------
            | Login Controller
            |--------------------------------------------------------------------------
            |
            | This controller handles authenticating users for the application and
            | redirecting them to your home screen. The controller uses a trait
            | to conveniently provide its functionality to your applications.
            |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    //protected $redirectTo = 'en/dashboard';
    protected function redirectTo()
    {
        $lang = '';
        if (Schema::hasTable('languages')) {
            $language = DB::table('languages')->where('status', '=', 1)->get();
            $lang = $language[0]->short_name;
            app()->setLocale($lang);
        } else {
            $lang = 'en';
            app()->setLocale($lang);
        }
        return $lang . '/dashboard';
    }
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //authenticated user has any role to use the system
        if (Auth::user() && Auth::user()->roles()->get()->isNotEmpty()) {
            (new SaveLoginTime())->execute();
        } else {
            Session::flash('error', trans('You dont have permission to access the system. Please contact your administrator.'));
            Auth::logout();
            return back();
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'official_email';
    }
    public function redirectToSlackProvider()
    {
        return Socialite::with('slack')->redirect();
    }
    public function handleSlackProviderCallback()
    {
        $user = Socialite::with('slack')->user();
    }
    public function redirectToProvider()
    {
        return Socialite::with('zoho')->redirect();
    }
    public function handleProviderCallback()
    {
        $user = Socialite::with('zoho')->user();
        // $user->token;
    }
}
