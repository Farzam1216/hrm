<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\DeleteEmployeeVisaDetails;
use App\Domain\Employee\Actions\StoreEmployeeVisaDetails;
use App\Domain\Employee\Actions\UpdateEmployeeVisaDetails;
use App\Domain\Employee\Actions\storeEmployeeNewVisaNotification;
use App\Domain\Employee\Actions\storeEmployeeVisaUpdateNotification;
use App\Domain\Employee\Actions\storeEmployeeVisaDeleteNotification;
use Illuminate\Http\Request;

class EmployeeVisaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmployeeVisaDetails())->execute($request);
        (new storeEmployeeNewVisaNotification())->execute($request);
        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeVisaUpdateNotification())->execute($request,$id);
        (new UpdateEmployeeVisaDetails())->execute($id, $request);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeVisaDeleteNotification())->execute($request,$id);
        (new DeleteEmployeeVisaDetails())->execute($id);

        return redirect()->back();
    }
}
