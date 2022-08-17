<?php

namespace App\Http\Controllers\Api;

use App;
use App\Domain\Employee\Actions\CreateEmploymentStatus;
use App\Domain\Employee\Actions\DeleteEmploymentStatus;
use App\Domain\Employee\Actions\GetAllEmploymentStatus;
use App\Domain\Employee\Actions\UpdateEmploymentStatus;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Http\Requests\validateEmploymentStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class EmploymentStatusController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index()
    {
        //$request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $employmentStatus = (new GetAllEmploymentStatus)->execute();
        if (!$employmentStatus->isEmpty()) {
            $this->responseData['response'] = 1;
            $this->responseData['message'] = "Employment Statuses has been Recieved.";
            $this->responseData['data'] = $employmentStatus;
            $this->status = 200;
        }
        return $this->apiResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param validateEmploymentStatus $request
     * @return RedirectResponse|Redirector
     */
    public function store(validateEmploymentStatus $request)
    {
        (new CreateEmploymentStatus())->execute($request);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect($locale.'/employmentstatus')->with('locale', $locale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function update($lang, validateEmploymentStatus $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateEmploymentStatus())->execute($id, $request);
        return redirect($locale.'/employmentstatus')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new DeleteEmploymentStatus())->execute($id);

        return redirect($locale.'/employmentstatus')->with('locale', $locale);
    }
}
