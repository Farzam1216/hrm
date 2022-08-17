<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests\validateJob;
use Illuminate\Http\RedirectResponse;
use App\Domain\Hiring\Models\Question;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Domain\Hiring\Actions\DeleteJob;
use App\Domain\Hiring\Models\JobOpening;
use Illuminate\Support\Facades\Redirect;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Hiring\Actions\GetJobOpening;
use App\Domain\Hiring\Actions\CreateJobOpening;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Hiring\Actions\EditJobOpening;
use App\Domain\Hiring\Actions\StoreJobQuestions;
use App\Domain\Hiring\Actions\UpdateJobQuestions;
use App\Domain\Hiring\Actions\StoreJobNotifications;
use App\Domain\Hiring\Actions\StoreJobUpdateNotifications;
use App\Domain\Hiring\Actions\StoreJobDeleteNotifications;

class JobOpeningController extends Controller
{
    /**
     * Show All Jobs List.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->forget('unauthorized_user');
        $jobs = (new GetJobOpening())->execute();
        $breadcrumbs = [
            ['link' => "$locale/job", 'name' => "Hiring "], ['name' => "Job Openings"]
        ];

        return view('admin.jobs.index')->with('jobs', $jobs)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Show Form Form Creating A New Job.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/job", 'name' => "Hiring "], ['name' => "Create Job "]
        ];

        $data = (new CreateJobOpening())->execute();
        return view('admin.jobs.create')
            ->with('designations', $data['designations'])
            ->with('departments',$data['departments'])
            ->with('locations',$data['locations'] )
            ->with('employees',$data['employees'] )
            ->with('employmentStatus', $data['employmentStatus'])
            ->with('questions', $data['questions'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs);
    }



    /**
     * Store New Job In Storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(validateJob $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreJobQuestions())->execute($request);
        (new StoreJobNotifications())->execute($request);
        Session::flash('success', trans('language.Job is created successfully'));

        return Redirect::to(url($locale.'/job'))->with('locale', $locale);
    }

    /**
     * Show Form For Update Specif Job details.
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit($locale, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        /*---- put these variables in service (they are dealing directly with Models) and return through array----*/
        $data = (new EditJobOpening())->execute($id);
        $breadcrumbs = [
            ['link' => "$locale/job", 'name' => "Hiring "], ['name' => "Edit Job "]
        ];
        return view('admin.jobs.edit')
        ->with('job', $data['job'])
        ->with('designations',$data['designations'] )
        ->with('departments', $data['departments'] )
        ->with('locations', $data['locations'])
        ->with('employees', $data['employees'])
        ->with('employmentStatus',$data['employmentStatus'] )
        ->with('jobquestions', $data['jobquestions'])
        ->with('allquestions', $data['allquestions'])
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update All Jobs List.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function update($lang, validateJob $request, $id)
    {
        
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new UpdateJobQuestions())->execute($request, $id);
        if($data){
            (new StoreJobUpdateNotifications())->execute($id);
            Session::flash('success', trans('language.Job is updated successfully'));
        }

        return Redirect::to(url($locale.'/job'))->with('locale', $locale);
    }

    /**
     * Delete Job From The System Of Specific ID.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreJobDeleteNotifications())->execute($id);
        $deleted=(new DeleteJob())->execute($id);
        if ($deleted) {
            Session::flash('success', trans('language.Job deleted successsfuly.'));
        }
        return redirect()->back();
    }
}
