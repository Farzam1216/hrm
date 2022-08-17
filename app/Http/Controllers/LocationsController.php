<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateLocation;
use App\Domain\Employee\Actions\DeleteLocation;
use App\Domain\Employee\Actions\EditLocation;
use App\Domain\Employee\Actions\GetAllCountries;
use App\Domain\Employee\Actions\GetAllLocations;
use App\Domain\Employee\Actions\UpdateLocation;
use App\Http\Requests\validateLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Domain\Hiring\Actions\StoreLocationNotifications;
use App\Domain\Hiring\Actions\StoreLocationUpdateNotifications;
use App\Domain\Hiring\Actions\StoreLocationDeleteNotifications;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public $weekDays = [
        'Monday' => 'Monday',
        'Tuesday' => 'Tuesday',
        'Wednesday' => 'Wednesday',
        'Thursday' => 'Thursday',
        'Friday' => 'Friday',
        'Saturday' => 'Saturday',
        'Sunday' => 'Sunday',
    ];

    /**
     * Show All locations List.
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $locations = (new GetAllLocations())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], [ 'name' => "Locations"]
          ];
        return view('admin.locations.index',['breadcrumbs' => $breadcrumbs,'locations'=> $locations,'locale'=> $locale]);
    }

    /**
     * Show the form for creating a new Locations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $countries=(new GetAllCountries())->execute();
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/locations",  'name' => "Locations"], ['name' => "Add Location"]
        ];
        return view('admin.locations.create',['breadcrumbs' => $breadcrumbs,'countries'=> $countries,'locale'=> $locale]);
    }

    /**
     * Store a newly created Locations in storage.
     *
     * @param validateLocation $request
     *
     * @return Response
     */
    public function store(validateLocation $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new CreateLocation())->execute($request);
        (new StoreLocationNotifications())->execute($request);
        return redirect($locale.'/locations')->with('locale', $locale);
    }

    /**
     * Show the form for editing the Locations.
     *
     * @param Locations
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Request $request, $locale, $location_id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new EditLocation())->execute($location_id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/locations",  'name' => "Locations"], ['name' => "Edit Location"]
        ];
        return view('admin.locations.edit',['breadcrumbs' => $breadcrumbs,
            'office_location' => $data['location'],
            'countries'=>$data['countries'],
            'locale'=> $locale]);
    }

    /**
     * Update the specified Locations in storage.
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function update(validateLocation $request, $locale, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreLocationUpdateNotifications())->execute($request,$id);
        (new UpdateLocation())->execute($id, $request);

        return redirect($locale.'/locations')->with('locale', $locale);
    }

    /**
     * Remove the specified Locations from storage.
     *
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreLocationDeleteNotifications())->execute($id);
        (new DeleteLocation())->execute($id);

        return redirect()->back()->with('locale', $locale);
    }
}
