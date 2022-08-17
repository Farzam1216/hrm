<?php

namespace App\Http\Controllers;

use App;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Domain\Attendance\Actions\GetCorrectionRequestById;
use App\Domain\Attendance\Actions\UpdateEmployeeAttendanceFromCorrectionRequest;

class EmployeeAttendanceCorrectionDecisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => $locale."/correction-requests", 'name' => "Correction Requests"], ['name' => 'Correction Request Decision']
        ];

        $correctionRequest = (new GetCorrectionRequestById())->execute($id);

        return view('admin.employee-attendance-correction.decision')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('date', Carbon::parse($correctionRequest->date))
        ->with('correctionRequest', $correctionRequest);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $locale, $correction_id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateEmployeeAttendanceFromCorrectionRequest())->execute($request, $correction_id);

        if ($data) {
            Session::flash('success', trans('language.Attendance correction request decision is updated successfully'));
            return redirect()->route('correction-requests.index', [$locale, $request->employee_id]);
        }

        if (!$data) {
            Session::flash('error', trans('language.Something went wrong while updating attendance correction request decision'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
