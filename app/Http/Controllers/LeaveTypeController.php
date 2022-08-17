<?php

namespace App\Http\Controllers;

use App;
use App\Domain\TimeOff\Models\LeaveType;
use App\Http\Requests\storeLeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Session;

class LeaveTypeController extends Controller
{
    /**
     * Show All Leave Types.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $leave_types = LeaveType::all();

        return view('admin.leave_types.index')->with('leave_types', $leave_types)->with('locale', $locale);
    }

    /**
     * Show Form For Creating Leave Type.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function create(storeLeaveType $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $leave_exist = LeaveType::where('name', $request->name)->first();
        if ($leave_exist == null) {
            LeaveType::create([
                'name' => $request->name,
                'count' => $request->amount,
                'status' => $request->status,
            ]);
            Session::flash('success', trans('language.Leave type is created successfully'));
        } else {
            Session::flash('error', trans('language.Leave type with this name already exist'));
        }

        return redirect($locale.'/leave-types')->with('locale', $locale);
    }

    /**
     * Update Leave Type.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $leave_type = LeaveType::find($id);
        $leave_type->name = $request->name;
        $leave_type->count = $request->amount;
        $leave_type->status = $request->status;
        $leave_type->save();
        Session::flash('success', trans('language.Leave type is updated successfully'));

        return redirect($locale.'/leave-types')->with('locale', $locale);
    }

    /**
     * Delete Leave Type.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function delete($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $leave_type = LeaveType::find($id);
        $leave_type->delete();
        Session::flash('success', trans('language.Leave type deleted successfully'));

        return redirect($locale.'/leave-types')->with('locale', $locale);
    }
}
