<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Http\Requests\validateEmailTemplate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use App\Domain\Hiring\Models\EmailTemplate;
use App\Domain\Hiring\Actions\SetWelcomeMail;
use App\Domain\Hiring\Actions\GetEmailTemplates;
use App\Domain\Hiring\Actions\StoreEmailTemplate;
use App\Domain\Hiring\Actions\DestoryEmailTemplate;
use App\Domain\Hiring\Actions\UpdateEmailTemplates;
use App\Domain\Hiring\Models\EmailTemplateAttachment;
use App\Domain\Hiring\Actions\StoreEmailTemplateNotification;
use App\Domain\Hiring\Actions\StoreEmailTemplateUpdateNotification;
use App\Domain\Hiring\Actions\StoreEmailTemplateDeleteNotification;
use App\Domain\Hiring\Actions\StoreWelcomeEmailTemplateNotification;
use App\Domain\Hiring\Actions\EditEmailTemplateAndAttachments;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $emailTemplate = (new GetEmailTemplates())->execute();
        $breadcrumbs = [
            ['link' => "$locale/emailtemplates", 'name' => "Settings | Hiring |  "], ['name' => "Email Templates"]
        ];
        return view('admin.email_templates.index')->with('emailTemplates', $emailTemplate)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/emailtemplates", 'name' => "Settings | Hiring |  "], ['name' => "Create Email Templates"]
        ];
        return view('admin.email_templates.create')
            ->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(validateEmailTemplate $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $response = (new StoreEmailTemplate())->execute($request);
        if ($response === true) {            
            (new StoreEmailTemplateNotification())->execute($request);
            Session::flash('success', trans('language.Email Template is created successfully'));
        } else {
            Session::flash('error', trans('language.Same Email Template already exist'));
        }

        return Redirect::to(url($locale . '/emailtemplates'))->with('locale', $locale);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($locale, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new EditEmailTemplateAndAttachments())->execute($id);
        $breadcrumbs = [
            ['link' => "$locale/emailtemplates", 'name' => "Settings | Hiring |  "], ['name' => "Edit Email Templates"]
        ];
        return view('admin.email_templates.edit')->with('emailTemplate', $data['emailTemplate'])->with('emailTemplateAttachment', $data['emailTemplateAttachment'])->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }
    public function setWelcomeEmail(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreWelcomeEmailTemplateNotification())->execute($request);
        $emailTemplate = (new SetWelcomeMail())->execute($request);
        return response()->json($emailTemplate);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update($locale, validateEmailTemplate $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmailTemplateUpdateNotification())->execute($request,$id);
        $response = (new UpdateEmailTemplates())->execute($locale, $request, $id);
        if ($response) {
            Session::flash('success', trans('language.Email Template is updated successfully'));
            return Redirect::to(url($locale . '/emailtemplates'))->with('locale', $locale);
        }
        Session::flash('success', trans('language.Email Template is updated successfully'));
        return Redirect::to(url($locale . '/emailtemplates'))->with('locale', $locale);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function destroy(Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmailTemplateDeleteNotification())->execute($id);
        (new DestoryEmailTemplate())->execute($id);
        Session::flash('success', trans('language.Email Template is deleted successfully'));
        return Redirect::to(url($locale . '/emailtemplates'))->with('locale', $locale);
    }
}
