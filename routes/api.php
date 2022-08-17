<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'auth:api'], function(){
    // Route::Get('employees', 'EmployeeController@index');
    // Route::get('/employees/create', 'EmployeeController@create')->middleware('has_full_permission:manage employees store');
    // Route::get('/employees/{id}', 'EmployeeController@show');
    // Route::get('/employees/{id}/edit', 'EmployeeController@edit');    
    // Route::PUT('/employees/{id}', 'EmployeeController@update');
    // Route::Post('/employees', 'EmployeeController@store')->middleware('has_full_permission:manage employees store');
    Route::apiResources([
        'employees' => 'Api\EmployeeController',
        'designations' => 'Api\DesignationsController',
        'departments' => 'Api\DepartmentsController',
        'branches' => 'Api\BranchesController',
        'access-level/employee' => 'Api\EmployeeAccessLevelController',
        'employment-statuses' => 'Api\EmploymentStatusController',

    ]);
    //Visa
    Route::get('/visas', 'EmployeeVisaController@index');
    Route::put('/visas/{id}', 'EmployeeVisaController@update');
    Route::post('/visas', 'EmployeeVisaController@store');
    Route::delete('/visas/{id}', 'EmployeeVisaController@destroy');
    //Assets
    Route::resource('employees.assets','AssetController')->shallow();
    //Education
    Route::resource('employees.educations','EducationController')->shallow();
});

Route::post('/login', 'ApiAuthController@login');
// Route::post('taskcategories/store', 'TaskCategoryController@store');
Route::resource('task-categories','Api\TaskCategoryController');
Route::apiResources(['document-types'=>'Api\DocumentTypeController']);

Route::group(['prefix' => '/v1'], function () {
    Route::apiResources([
        'Employee' => 'Api\EmployeeController',
        'Employee/Docs' => 'Api\EmployeeDocumentController',
        'Employee/Assets' => 'Api\EmployeeAssetsController',
        'Employee/Notes' => 'Api\EmployeeNotesController',
        'Branches' => 'Api\BranchesController',
        'Departments' => 'Api\DepartmentsController',
        'Designations' => 'Api\DesignationsController',
        'Attendance' => 'Api\AttendanceController',
        'AttendanceBreaks' => 'Api\AttendanceBreaksController',
        'Leaves' => 'Api\LeaveController',
        'Vendor' => 'Api\VendorController',
        'VendorCategory' => 'Api\VendorCategoryController',
        'SalaryTemplate' => 'Api\SalaryTemplateController',
        'Allowance' => 'Api\AllowanceController',
        'Deduction' => 'Api\DeductionController',
        'Team' => 'Api\TeamController',
        'TeamMember' => 'Api\TeamMemberController',
        'Jobs' => 'Api\JobController',
        'LeaveType' => 'Api\LeaveTypeController',
        'JobApplicant' => 'Api\ApplicantController',
    ]);
    Route::get('Applicant/trashed', 'Api\JobHiringController@trashed');
    Route::get('Applicant/kill/{id}', 'Api\JobHiringController@kill');
    Route::get('Applicant/restore/{id}', 'Api\JobHiringController@restore');
    Route::get('Applicant/hiredApplicants', 'Api\JobHiringController@hiredApplicants');
    Route::get('Applicant/hire/{id}', 'Api\JobHiringController@hire');
    Route::get('Applicant/retire/{id}', 'Api\JobHiringController@retire');
});
