<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Domain\Employee\Actions\storeUserAccountNotificaiton;
use Session;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Employee Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "User Account"], ['name' => "Show"]
        ];
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = Employee::find(Auth::user()->id);

        return view('admin.users.profile')->with('employee', $employee)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Edit Employee Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function edit(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => $locale."/personal-profile", 'name' => "User Account"], ['name' => "Edit"]
        ];

        $employee = Employee::find(Auth::user()->id);

        return view('admin.users.edit')->with('employee', $employee)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Edit Employee Details.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = Employee::find($request->id);
        if($request->current_password)
        {
            if (!(Hash::check($request->current_password, Auth::user()->password))) {
                // The passwords matches
                return redirect()->back()->with('error', 'Your current password does not matches with the password you provided. Please try again.');
            }

            if ($request->current_password == $request->new_password) {
                //Current password and new password are same
                return redirect()->back()->with('error', 'New Password cannot be same as your current password. Please choose a different password.');
            }

            if ($request->new_password !== $request->new_password_confirmation) {
                //Current password and new password are same
                return redirect()->back()->with('error', 'Your new password does not matches with the confirmation password you provided. Please try again.');
            }

            $employee->password = Hash::make($request->new_password);
        }

        $employee->firstname = $request->first_name;
        $employee->lastname = $request->last_name;
        $employee->official_email = $request->email;

        if ($request->file('picture'))
        {
            $picture = time() . '_' . $request->file('picture')->getClientOriginalName();
            $destination = 'storage/employees/profile/';
            $request->picture->move($destination, $picture);
            $employee->picture = $destination . $picture;
        }
        (new storeUserAccountNotificaiton)->execute($request);
        $employee->save();

        Session::flash('success', trans('Details are updated successfully.'));
        return back();
    }
}
