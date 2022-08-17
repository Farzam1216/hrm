<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Providers\MailConfigServiceProvider;
use App\Domain\SmtpDetail\Actions\GetAllSmtpDetails;
use App\Domain\SmtpDetail\Actions\StoreSmtpDetails;
use App\Domain\SmtpDetail\Actions\GetSmtpDetailById;
use App\Domain\SmtpDetail\Actions\UpdateSmtpDetailById;
use App\Domain\SmtpDetail\Actions\DestroySmtpDetailById;
use App\Http\Requests\storeSmtpDetails as storeSmtpDetailsValidation;
use App\Http\Requests\updateSmtpDetails as updateSmtpDetailsValidation;

class SmtpDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Smtp Details"]
        ];

        $smtp_details = (new GetAllSmtpDetails())->execute();

        return view('admin.smtp_details.index',[
            'locale' => $locale,
            'breadcrumbs' => $breadcrumbs,
            'smtp_details'=> $smtp_details,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => route('smtp-details.index', [$locale]), 'name' => "Smtp Details"], ['name' => "Create"]
        ];$data = (new MailConfigServiceProvider(''))->boot();

        return view('admin.smtp_details.create',[
            'locale' => $locale,
            'breadcrumbs' => $breadcrumbs,
            'config' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeSmtpDetailsValidation $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreSmtpDetails())->execute($request);

        if (isset($data['error'])) {
            if (str_contains($data['error']->getMessage(), 'transport')) {
                Session::flash('error', trans('language.Mail driver is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'No such host is known')) {
                Session::flash('error', trans('language.Mail host is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'The requested address is not valid in its context')) {
                Session::flash('error', trans('language.Mail port is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'Invalid login or password')) {
                Session::flash('error', trans('language.Mail username or password is invalid'));
            }

            return back()->withInput($request->all());
        }

        if ($data) {
            Session::flash('success', trans('language.Smtp detail is stored successfully'));
            return redirect()->route('smtp-details.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went wrong while storing smtp detail'));
            return back()->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => route('smtp-details.index', [$locale]), 'name' => "Smtp Details"], ['name' => "Show"]
        ];

        $smtp_detail = (new GetSmtpDetailById())->execute($id);

        return view('admin.smtp_details.show',[
            'locale' => $locale,
            'breadcrumbs' => $breadcrumbs,
            'smtp_detail' => $smtp_detail
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => route('smtp-details.index', [$locale]), 'name' => "Smtp Details"], ['name' => "Edit"]
        ];

        $smtp_detail = (new GetSmtpDetailById())->execute($id);

        return view('admin.smtp_details.edit',[
            'locale' => $locale,
            'breadcrumbs' => $breadcrumbs,
            'smtp_detail' => $smtp_detail
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateSmtpDetailsValidation $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateSmtpDetailById())->execute($request, $id);

        if (isset($data['error'])) {
            if (str_contains($data['error']->getMessage(), 'transport')) {
                Session::flash('error', trans('language.Mail driver is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'No such host is known')) {
                Session::flash('error', trans('language.Mail host is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'The requested address is not valid in its context')) {
                Session::flash('error', trans('language.Mail port is invalid'));
            }

            if (str_contains($data['error']->getMessage(), 'Invalid login or password')) {
                Session::flash('error', trans('language.Mail username or password is invalid'));
            }

            return back()->withInput($request->all());
        }

        if ($data) {
            Session::flash('success', trans('language.Smtp detail is updated successfully'));
            return redirect()->route('smtp-details.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went wrong while updating smtp detail'));
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new DestroySmtpDetailById())->execute($request, $id);
        
        if ($data) {
            Session::flash('success', trans('language.Smtp detail is deleted successfully'));
            return redirect()->route('smtp-details.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went wrong while deleting smtp detail'));
            return back();
        }
    }
}
