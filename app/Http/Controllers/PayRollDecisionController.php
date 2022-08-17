<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Domain\PayRoll\Actions\GetPayRollById;
use App\Domain\PayRoll\Actions\StoreSinglePayRollDecisionById;
use App\Domain\PayRoll\Actions\StoreMultiplePayRollDecisionById;

class PayRollDecisionController extends Controller
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
            ['link' => "javascript:void(0)", 'name' => "Payroll"], ['link' => 'en/payroll-management', 'name' => "All Employees Payroll"], ['name' => "Decision"]
        ];

        if (isset($request->ids)) {
            $payrolls = (new GetPayRollById())->execute($request->ids);

            return view('admin.payroll_management.payroll_decision.create',[
                'locale' => $locale,
                'breadcrumbs' => $breadcrumbs,
                'payrolls' => $payrolls,
                'ids' => $request->ids
            ]);
        } else {
            $payrolls = (new GetPayRollById())->execute($id);

            return view('admin.payroll_management.payroll_decision.create',[
                'locale' => $locale,
                'breadcrumbs' => $breadcrumbs,
                'payrolls' => $payrolls,
                'id' => $id
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $locale)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Payroll"], ['link' => 'en/payroll-management', 'name' => "All Employees Payroll"], ['name' => "Decision"]
        ];

        if (isset($request->id)) {
            $payroll = (new StoreSinglePayRollDecisionById())->execute($request);

            if ($payroll) {
                Session::flash('success', trans('language.Payroll decision is stored successfully'));
            }

            if (!$payroll) {
                Session::flash('success', trans('language.Something went wrong while storing payroll decision'));

                return redirect()->back()->withInput($request->all());
            }
        }

        if (isset($request->ids)) {
            $payroll = (new StoreMultiplePayRollDecisionById())->execute($request);

            if ($payroll) {
                Session::flash('success', trans('language.Multiple payroll decision is stored successfully'));
            }

            if (!$payroll) {
                Session::flash('success', trans('language.Something went wrong while storing multiple payroll decision'));

                return redirect()->back()->withInput($request->all());
            }
        }

        return redirect($locale.'/payroll-management')->with('locale', $locale); 
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
