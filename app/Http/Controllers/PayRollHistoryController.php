<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Domain\PayRoll\Actions\getFilteredPayroll;
use App;
use App\Domain\PayRoll\Actions\GetPayRollHistory;
use App\Domain\Attendance\Actions\getFiltersData;

class PayRollHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Payroll Management"],    
            ['name' => "Payrolls History"]
         ];
         $id='';
         $payrollHistory = (new GetPayRollHistory())->execute();
         $data = (new getFiltersData())->execute($id);
         return view('admin.payroll_management.payroll_history.index',[
            'breadcrumbs' => $breadcrumbs,
            'payroll'=> $payrollHistory,
            'employees' => $data['employees'],
            'months' => $data['months'],
            'years' => $data['years']
        ]);
    }

    public function filterPayrollHistory(Request $request)
    {
        //
        $payrollHistory = (new getFilteredPayroll)->execute($request);

        return response()->json($payrollHistory);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
