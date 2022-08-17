<?php

namespace App\Http\Controllers;

use App;

use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\Location;
use App\Domain\Hiring\Actions\GetCandidates;
use App\Domain\Hiring\Actions\SendEmail;
use App\Domain\Hiring\Actions\SingleCandidateView;
use App\Domain\Hiring\Actions\StoreNewCandidate;
use App\Domain\Hiring\Models\Candidate;
use App\Domain\Hiring\Models\CandidateStatus;
use App\Domain\Hiring\Models\JobOpening;
use App\Domain\Hiring\Models\Question;
use App\Http\Requests\storeCandidate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Domain\Hiring\Actions\StoreCandidateRatingNotifications;
use App\Domain\Hiring\Actions\StoreNewCandidateNotification;
use Session;

;

class CandidateController extends Controller
{
    public $statusArray= ['reviewed'=>'Reviewed', 'schedulephone'=> 'Schedule Phone Screen', 'phone'=> 'Phone Screened','scheduleinterview' => 'Schedule Interview',
        'interview' => 'Interviewed', 'hold'=> 'Put on Hold', 'reference'=> 'Checking References', 'notfit'=> 'Not a Fit',
        'declinedoffer'=> 'Declined Offer', 'notqualified'=> 'Not Qualified', 'overqualified'=> 'Over Qualified',
        'hiredelsewhere'=>'Hired Elsewhere', 'hire'=>'Hire'];
    /**
     * List Of Candidates.
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/candidates", 'name' => "Candidate Management"], ['name' => "Candidates"]
        ];
        $candidates=(new GetCandidates())->execute();
        return view('admin.candidates.index')
            ->with('candidates', $candidates)
            ->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Candidate Form For Applying To A Job.
     */
    public function create()
    {
        session()->forget('unauthorized_user');
        $Jobs=JobOpening::with('designation', 'locations', 'employmentStatus', 'que')->get();
        $PivotQuestion=[];
        $count=0;
        foreach ($Jobs as $job) {
            $PivotQuestion[$count]=$job->que;
            $count++;
        }
        $branches=Location::all();
        

        return view('applicant.create')->with('questions', Question::all())->with('branches', $branches)->with('jobs', $Jobs)->with('pivotquestions', $PivotQuestion);
    }

    /**
     * @param storeCandidate $request
     * @return RedirectResponse
     * Store Candidate Data In the System.
     */
    public function store(storeCandidate $request)
    {
        
        (new StoreNewCandidateNotification())->execute($request);
        (new StoreNewCandidate())->execute($request);
        Session::flash('success', 'Application is submitted successfully');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $lang
     * @param $id
     * @return RedirectResponse
     */
    public function sendEmail(Request $request, $lang, $id)
    {
        (new SendEmail())->execute($request, $id);
        Session::flash('success', 'Email is send Successfully');
        return redirect()->back();
    }

    /**
     * Single Candidate View.
     * @param $lang
     * @param $id
     * @return Factory|View
     */
    public function singleCandidate($lang, $id)
    {
        App::setLocale($lang);
        $data =  (new SingleCandidateView())->execute($lang, $id);
        $breadcrumbs = [
            ['link' => "$lang/candidate/{}", 'name' => "Candidate Management"], ['name' => "Candidate Details"]
        ];
        return view('admin/candidates/singleCandidate')
            ->with('candidate', $data['candidate'])
            ->with('locale', $lang)
            ->with('difference', $data['difference'])
            ->with('status', $data['status'])
            ->with('currentStatus', $data['currentStatus'])
            ->with('currentRating', $data['currentRating'])
            ->with('candidateAnswers', $data['candidateAnswers'])
            ->with('employee', Employee::all())
            ->with('emails', $data['emails'])
            ->with('allTemplates', $data['allTemplates'])
            ->with('statusArray', $this->statusArray)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Delete Candidate From the Record.
     * @param $lang
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy($lang, $id, Request $request)
    {
        $candidate=Candidate::with('answers')->where('id', $id)->first();

        foreach ($candidate->answers as $answers) { //Delete answers of candidate
            $answers->delete();
        }
        $candidate->delete();
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Candidate deleted successfully'));

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Candidate With The Trashed Status.
     * @param Request $request
     * @return Factory|View
     */
    public function trashed(Request $request)
    {
        $candidates=Candidate::onlyTrashed()->get();
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.candidates.trashed')->with('candidates', $candidates)->with('locale', $locale);
    }

    /**
     * For Permanent Delete From Trashed List.
     *
     * @param $lang
     * @param $id
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function kill($lang, $id, Request $request)
    {
        $candidate=Candidate::withTrashed()->where('id', $id)->first();
        $candidate->forceDelete();
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Candidate deleted permanently'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Restore Candidate Form Trashed List.
     *
     * @param $lang
     * @param $id
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore($lang, $id, Request $request)
    {
        $candidate=Candidate::withTrashed()->where('id', $id)->first();
        $candidate->restore();
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Successfully Restored the candidate'));

        return redirect()->back()->with('locale', $locale);
    }
    public function updatestatus(Request $request)
    {
        $data=CandidateStatus::create($request->all());
        (new StoreCandidateRatingNotifications())->execute($request);
        return response()->json($data);
    }

    /**
     * For Change Status Of Candidate To Hired.
     */
    public function hire($lang, $id, Request $request)
    {
        $candidate=Candidate::find($id);

        $candidate->recruited=1;
        $candidate->save();
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * For Change Candidate Status To Hired.
     * @param $lang
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function retire($lang, $id, Request $request)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $candidate=Candidate::find($id);
        $candidate->recruited=0;
        $candidate->save();

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * For Candidate List Whose Status are hired.
     * @param Request $request
     * @return Factory|View
     */
    public function hiredApplicants(Request $request)
    {
        $locale=$request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $candidates=Candidate::where('recruited', 1)->take(10)->get();

        return view('admin.candidates.hiredApplicants')->with('candidates', $candidates)->with('locale', $locale);
    }
}
