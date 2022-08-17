<?php
namespace App\Http\Controllers;

use App;
use App\Models\SMTP;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Config The SMTP  Details
     * @param Request $req
     * @return RedirectResponse
     */
    public function smtp(Request $req)
    {
        $req->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $req->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request = SMTP::first();
        if (isset($request)) {
            Config::set('mail.driver', $request->driver);
            Config::set('mail.host', $request->host);
            Config::set('mail.port', $request->port);
            Config::set('mail.username', $request->user);
            Config::set('mail.password', $request->password);
            Config::set('mail.sendmail', $request->sendmail);
        }
        return view('admin.smtp.index')->with('config', $request)->with('locale', $locale);
    }

    /**
     * Update and Config SMTP Details
     * @param Request $request
     * @return RedirectResponse
     */
    public function smtpUpdate(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Config::set('mail.driver', $request->driver);
        Config::set('mail.host', $request->host);
        Config::set('mail.port', $request->port);
        Config::set('mail.username', $request->user);
        Config::set('mail.password', $request->password);
        Config::set('mail.sendmail', $request->sendmail);
        $data = SMTP::first();
        if ($data == null) {
            $data = new SMTP();
        }
        $data->driver = $request->driver;
        $data->host = $request->host;
        $data->port = $request->port;
        $data->user = $request->user;
        $data->password = $request->password;
        $data->sendmail = $request->sendmail;
        $data->save();
        return redirect('/smtp')->with('locale', $locale);
    }
}
