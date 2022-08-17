<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Route;

// Route::get('/{any}', 'ApplicationController')->where('any', '.*');

Route::get('/', function () {
    /* FIXME: DB checks shouldn't be in here
    if (Schema::hasTable('languages')) {
        $language = DB::table('languages')->where('status', '=', 1)->get();
        $lang = $language[0]->short_name;
        app()->setLocale($lang);
    } else {
        $lang = 'en';
        app()->setLocale($lang);
    }
    */

    $lang = 'en';
    app()->setLocale($lang);

    return view('auth.login')->with('locale', $lang);
});

Route::get('/slack', 'Auth\LoginController@redirectToSlackProvider');
Route::get('/slack_login', 'Auth\LoginController@handleSlackProviderCallback');
Route::get('/zoho', 'Auth\LoginController@redirectToProvider');
Route::get('/zoho_login', 'Auth\LoginController@handleProviderCallback');

Auth::routes();

Route::post('/password/reset', 'Auth\ForgotPasswordController@SendsPasswordResetLink')->name('password.reset.email');
Route::post('reset/employee/password', 'Auth\ResetController@reset')->name('reset.employee.password');

Route::any('/register', function () {
    abort(403);
});

Route::get('/error', function () {
    return view('error');
})->name('error');

//
//Route::get('/home', 'HomeController@index');

// TEMPORARY
Route::get('/request-time-off', function () {
    $lang = 'en';
    app()->setLocale($lang);

    return view('admin.request_time_off.index')->with('locale', $lang);
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('{lang}/employees/attendance-filter', 'EmployeeAttendanceController@filter')->name('employees.employee-attendance.filter');
    Route::resource('{lang}/employees.employee-attendance', 'EmployeeAttendanceController')->middleware('has_full_permission:view employee_attendance|edit employee_attendance');
    Route::resource('{lang}/employee-attendance', 'EmployeeAttendanceController');

    Route::group(['middleware' => 'has_full_permission:manage employees attendance'], function () {
        Route::resource('{lang}/employee-attendance.comments', 'EmployeeAttendanceCommentsController');
    });

    Route::group(['middleware' => 'has_full_permission:manage payroll management'], function () {
        // payroll
        Route::get('{lang}/payroll-management', 'PayRollController@index');
        Route::get('{lang}/employees/{id}/pay-roll', 'PayRollController@edit');
        Route::post('{lang}/employees/{id}/pay-roll/store', 'PayRollController@store');
        Route::post('{lang}/employees/{id}/pay-roll/update', 'PayRollController@update');
        Route::get('{lang}/payroll-delete', 'PayRollController@deletePayroll');
        Route::delete('{lang}/payroll-delete/{id}', 'PayRollController@destroy');
        Route::resource('{lang}/payroll-history', 'PayRollHistoryController');
        Route::get('{lang}/filter-payroll-history', 'PayRollHistoryController@filterPayrollHistory');
        Route::resource('{lang}/payroll-management.decision', 'PayRollDecisionController');
        Route::get('{lang}/payroll-management/getFilteredPayroll', 'PayRollController@getFilteredPayrollsByAJAX');
    });


    //Hiring (Create/Edit Job and manage candidates
    Route::group(['middleware' => 'has_full_permission:manage hiring jobopening_candidates'], function () {
        //Candidates
        Route::Get('{lang}/candidates', [
            'uses' => 'CandidateController@index',
            'as' => 'candidates',
        ]);
        Route::Get('{lang}/candidate/single-Cat-Job/{id}', [
            'uses' => 'CandidateController@singleCatJob',
            'as' => 'single_cat_jobs',
        ]);

        Route::Get('{lang}/candidate/delete/{id}', [
            'uses' => 'CandidateController@destroy',
            'as' => 'candidate.delete',
        ]);
        Route::Get('{lang}/candidate/trashed', [
            'uses' => 'CandidateController@trashed',
            'as' => 'candidate.trashed',
        ]);
        //SingleCandidate
        Route::Get('{lang}/candidate/{id}', [
            'uses' => 'CandidateController@singleCandidate',
            'as' => 'candidate.single',
        ]);
        //SendEmailtoCandidate
        Route::Post('{lang}/candidate/send-email/{id}', [
            'uses' => 'CandidateController@sendEmail',
            'as' => 'candidate.sendEmail',
        ]);

        Route::Get('{lang}/candidate/kill/{id}', [
            'uses' => 'CandidateController@kill',
            'as' => 'candidate.kill',
        ]);
        Route::Get('{lang}/candidate/restore/{id}', [
            'uses' => 'CandidateController@restore',
            'as' => 'candidate.restore',
        ]);
        Route::Get('{lang}/candidate/hire/{id}', [
            'uses' => 'CandidateController@hire',
            'as' => 'candidate.hire',
        ]);

        Route::Get('{lang}/candidate/retire/{id}', [
            'uses' => 'CandidateController@retire',
            'as' => 'candidate.retire',
        ]);
        Route::Get('{lang}/candidates/hired', [
            'uses' => 'CandidateController@hiredApplicants',
            'as' => 'candidates.hired',
        ]);

        Route::get('/updatestatus', 'CandidateController@updatestatus');
        //Jobs
        Route::resource(
            '{lang}/job',
            'JobOpeningController'
        );
    });

    //Job Application Form
    Route::Get('/applicant/apply', [
        'uses' => 'CandidateController@create',
        'as' => 'applicant.apply',
    ]);
    Route::Post('/applicant/store', [
        'uses' => 'CandidateController@store',
        'as' => 'applicant.store',
    ]);

    // payroll
    Route::group(['prefix' => '{lang}/attendance-management'], function () {
        Route::resource('pay-roll', 'PayRollController')->middleware('has_full_permission:manage employees attendance');
    });

    // Settings Tab Routes
    Route::group(['prefix' => '{lang}/'], function () {
        //Education Type
        Route::resource('education-types', 'EducationTypeController')->middleware('has_full_permission:manage setting education type');

        //Assets Type
        Route::get('asset-types', 'AssetsTypeController@index')->name('asset-types')->middleware('has_full_permission:manage setting asset type');

        Route::get('asset-types/create', [
        'uses' => 'AssetsTypeController@create',
        'as' => 'assetsType.create',
        ])->middleware('has_full_permission:manage setting asset type');

        Route::post('asset-types/store', [
            'uses' => 'AssetsTypeController@store',
            'as' => 'assetsType.store',
        ])->middleware('has_full_permission:manage setting asset type');

        Route::get('asset-types/edit/{id}', [
            'uses' => 'AssetsTypeController@edit',
            'as' => 'assetsType.edit',
        ])->middleware('has_full_permission:manage setting asset type');

        Route::post('asset-types/update/{id}', [
            'uses' => 'AssetsTypeController@update',
            'as' => 'assetsType.update',
        ])->middleware('has_full_permission:manage setting asset type');

        Route::get('asset-types/delete/{id}', [
            'uses' => 'AssetsTypeController@delete',
            'as' => 'assetsType.delete',
        ])->middleware('has_full_permission:manage setting asset type');

        //Visa Type
        Route::resource('visa-types', 'VisaTypeController')->middleware('has_full_permission:manage setting visa type');

        //Work Schedule
        Route::resource('work-schedule', 'WorkScheduleController')->middleware('has_full_permission:manage employees work_schedule');
        Route::get('assign/work-schedule/{id}/create', 'AssignWorkScheduleController@create')->name('assign-work-schedule.create');
        Route::post('assign/work-schedule/{id}', 'AssignWorkScheduleController@store')->name('assign-work-schedule.store');

        Route::group(['middleware' => 'has_full_permission:manage employees attendance'], function () {
            Route::resource('employee-attendance-approval', 'AttendanceApprovalController');
        });

        //Document
        Route::resource('documents', 'DocumentsController')->middleware('has_full_permission:manage setting document');

        //Document Type
        Route::resource('doc-types', 'DocumentTypeController')->middleware('has_full_permission:manage setting document type');

        //Onboarding, Offboarding and Tasks
        Route::group(['middleware' => 'has_full_permission:manage setting offboarding|manage setting onboarding'], function () {
            Route::resources(['tasks' => 'TaskController',]);
            Route::resources(['onboarding-tasks' => 'OnboardingTaskController']);
            Route::resources(['offboarding-tasks' => 'OffboardingTaskController']);

            Route::get('delete/task_template/document', 'TaskController@destroyTaskTemplate');

            //Offboarding and Onboarding Category
            Route::resource('task-categories', TaskCategoryController::class);
            Route::resource('onboarding-categories', OnboardingCategoryController::class);
            Route::resource('offboarding-categories', OffboardingCategoryController::class);
        });

        //Job Location
        Route::resource('locations', 'LocationsController')->middleware('has_full_permission:manage setting location');

        //Job Department
        Route::resource('departments', 'DepartmentController')->middleware('has_full_permission:manage setting department');
    
        //Job Employment Status
        Route::resource('employment-status', 'EmploymentStatusController')->middleware('has_full_permission:manage setting employment status');

        //Job Designation
        Route::resource('designations', 'DesignationController')->middleware('has_full_permission:manage setting designation');

        //Job Division
        Route::resource('divisions', 'DivisionController')->middleware('has_full_permission:manage setting division');

        //Benefit Plan and Group
        Route::group(['middleware' => 'has_full_permission:manage setting benefit'], function () {
            //Benefit Plan
            Route::resource('benefit-plan', 'BenefitPlanController');
            Route::get('benefit-plan/create/{type}', 'BenefitPlanController@create');
            Route::get('benefit-plan/edit/{type}/{data}', 'BenefitPlanController@edit');
            Route::get('benefit-plan/duplicateBenefitPlan/{id}', 'BenefitPlanController@duplicateBenefitPlan');
            Route::post('benefit-plan/duplicateBenefitPlan/{id}/save', 'BenefitPlanController@saveDuplicateBenefitPlan');

            //Benefit Group
            Route::resource('benefitgroup', 'BenefitGroupController');

            //Benefit Group Plan
            Route::resource('benefitgroupplan', 'BenefitGroupPlanController');
            Route::get('benefitgroup/duplicate/{id}', 'BenefitGroupController@duplicateBenefitGroup');
        });

        //Time Off Type and Policy
        Route::group(['middleware' => 'has_full_permission:manage setting time_off'], function () {
            //Time Off Types
            Route::resources(['time-off-type' => 'TimeOffTypeController',]);

            //Time Off Policy
            Route::group(['prefix' => 'timeoff'], function () {
                //Load employees to assign policy in TimeOff
                Route::get('policy/{policy}/assign', 'TimeOffController@assign')->name('policy.assign');
                Route::get('policy/{policy}/employees', 'TimeOffController@assignedEmployees')->name('policy.assigned_employees');
                //Save employee with assigned policy
                Route::post('policy/{id}/assigned', 'TimeOffController@storeAssignedEmployees')->name('policy.store_assigned_employees');
                Route::resource('policy', 'TimeOffController');
            });
        });

        //Hiring
        Route::group(['middleware' => 'has_full_permission:manage setting hiring'], function () {
            //QuestionType
            Route::resources(['question-types' => 'QuestionTypeController',]);

            //Question
            Route::resources(['questions' => 'QuestionController',]);

            //Email Templates
            Route::resources(['emailtemplates' => 'EmailTemplateController',]);
        });

        // Language
        Route::get('language', 'LanguageController@index')->middleware('has_full_permission:manage setting language');
        Route::post('language/create', 'LanguageController@create')->middleware('has_full_permission:manage setting language');
        Route::post('language/update/{id}', 'LanguageController@update')->middleware('has_full_permission:manage setting language');
        Route::post('language/delete/{id}', 'LanguageController@destroy')->middleware('has_full_permission:manage setting language');

        //Secondary Language
        Route::resource('secondarylanguage', 'SecondaryLanguageController')->middleware('has_full_permission:manage setting secondary language');
    
        //Approvals
        Route::group(['middleware' => 'has_full_permission:manage setting approval'], function () {
            //Approvals
            Route::get('approvals/delete/{id}', 'ApprovalController@destroy'); //FIXME:
            Route::resource('approvals', 'ApprovalController');
            Route::get('approvals/disable/{id}', 'ApprovalController@disable');
            Route::get('approvals/enable/{id}', 'ApprovalController@enable');
            Route::get('approvals/restore-approval/{id}', 'ApprovalController@restoreDefaultStandardApproval');
            //Approval Workflows
            Route::get('approval-workflows/{id}', 'ApprovalWorkflowController@destroy'); //FIXME:
            Route::resource('approval-workflows', 'ApprovalWorkflowController');
            //Advance Approval
            Route::get('advance-approvals/{id}/create', 'AdvanceApprovalController@create');
            Route::resource('advance-approvals', 'AdvanceApprovalController');
        });

        //SMTP
        Route::resource('smtp-details', 'SmtpDetailController')->middleware('has_full_permission:manage setting smtp details');
        //SMTP Status
        Route::resource('smtp-details.status', 'SmtpDetailStatusController')->middleware('has_full_permission:manage setting smtp details');
    });

    //Benefit Settings AJAX Calls
    Route::group(['middleware' => 'has_full_permission:manage setting benefit'], function () {
        //Benefit Group
        Route::get('/getGroupPlan', 'BenefitGroupController@getGroupPlan');
        Route::POST('/addavailableplan', 'BenefitGroupController@addAvailablePlan');

        //Benefit Group Plan
        Route::post('/updateplan', 'BenefitGroupPlanController@updatePlan'); //change
        Route::get('/getplan', 'BenefitGroupPlanController@getBenefitPlanAJAX');
    });

    Route::group(['middleware' => 'admin'], function () {
        //Access Level
        // Route::get('{lang}/access-level/add-employee/{role_id}', 'AccessLevelController@AddEmployeeInAccessLevel');
        Route::resource('{lang}/access-level', 'AccessLevelController');
        //Access level User
        Route::resource('{lang}/access-levels/addemployee', 'AccessLevelUserController');
        Route::get('{lang}/access-levels/addemployee/{role_id}/role/{employee_id}', 'AccessLevelUserController@update');
        Route::post('{lang}/access-levels/assign-multiple-roles', 'AccessLevelUserController@assignMultipleRoles');
        Route::get('{lang}/access-levels/create-non-employee', 'AccessLevelUserController@createNonEmployeeUser');
        Route::post('{lang}/access-levels/store-non-employee', 'AccessLevelUserController@storeNonEmployeeUser');

        //Manager Access Level
        Route::resource('{lang}/access-level/manager', 'ManagerAccessLevelController');
        Route::get('{lang}/access-level/employee/duplicate/{id}', 'EmployeeAccessLevelController@duplicate');
        Route::get('{lang}/access-level/manager/duplicate/{id}', 'ManagerAccessLevelController@duplicate');
        Route::get('{lang}/access-level/custom/duplicate/{id}', 'CustomAccessLevelController@duplicate');
        //Employee Access Level
        Route::resource('{lang}/access-level/employee', 'EmployeeAccessLevelController');
        //custom Access Level
        Route::resource('{lang}/access-level/custom', 'CustomAccessLevelController');

        //Assets
        Route::get('employees/{id}/assets', 'AssetController@index');
        Route::get('employees/{id}/assets/create', 'AssetController@create');
        Route::post('assets/store', 'AssetController@store');
        Route::get('employees/{employee_id}/assets/edit/{asset_id}', 'AssetController@edit');
        Route::post('employees/{employee_id}/assets/update/{asset_id}', 'AssetController@update');
        Route::post('employees/{employee_id}/assets/{asset_id}/delete', 'AssetController@destroy');

        //Division Members
        Route::post('{lang}/division-member/add', [
            'uses' => 'DivisionMemberController@create',
            'as' => 'division_member.add',
        ]);
        Route::get('{lang}/division-member/edit/{id}', [
            'uses' => 'DivisionMemberController@edit',
            'as' => 'division_member.edit',
        ]);
        Route::post('{lang}/division-member/delete/{id}', [
            'uses' => 'DivisionMemberController@delete',
            'as' => 'division_member.delete',
        ]);

        Route::post('{lang}/designation/delete_all', [
            'uses' => 'DesignationController@delete_all',
            'as' => 'designation.delete_all',
        ]);

        //        Route::get($lang . '/asset_type/edit/{id}', [
        //            'uses' => 'AssetsTypeController@edit',
        //            'as' => 'assetsType.edit',
        //        ]);
        //        Route::get($lang . '/employee/asset/{id}', [
        //            'uses' => 'AssetController@index',
        //            'as' => 'employee.assets',
        //        ]);

        //        Route::post($lang . '/asset/update/{id}', [
        //            'uses' => 'AssetController@update',
        //            'as' => 'employee.assetsUpdate',
        //        ]);
        //        Route::post($lang . '/asset/delete/{id}', [
        //            'uses' => 'AssetController@destroy',
        //            'as' => 'employee.assetsDelete',
        //        ]);


        //        //Assets_Type
        //        Route::get('asset-types', 'AssetsTypeController@index');
        //
        //        //Assets
        //        Route::get('employees/{id}/assets', 'AssetController@index');
        //        Route::get('employees/{id}/assets/create', 'AssetController@create');
        //        Route::post('assets/store', 'AssetController@store');
        //        Route::get('employees/{employee_id}/assets/edit/{asset_id}', 'AssetController@edit');
        //        Route::post('employees/{employee_id}/assets/update/{asset_id}', 'AssetController@update');
        //        Route::post('employees/{employee_id}/assets/{asset_id}/delete', 'AssetController@destroy');
        //        Route::get($lang . '/asset_type/edit/{id}', [
        //            'uses' => 'AssetsTypeController@edit',
        //            'as' => 'assetsType.edit',
        //        ]);
        //        Route::get($lang . '/employee/asset/{id}', [
        //            'uses' => 'AssetController@index',
        //            'as' => 'employee.assets',
        //        ]);

        //        Route::post($lang . '/asset/update/{id}', [
        //            'uses' => 'AssetController@update',
        //            'as' => 'employee.assetsUpdate',
        //        ]);
        //        Route::post($lang . '/asset/delete/{id}', [
        //            'uses' => 'AssetController@destroy',
        //            'as' => 'employee.assetsDelete',
        //        ]);
    });

    Route::resource('{lang}/inbox/approval-requests.approve', 'RequestLifeCycleController');
    Route::post('/approval-requests/{requestedDataID}/approve/{status}', 'RequestLifeCycleController@requestStatusChange');
    //Routes with Field-Level Permissions
    //---------------------------------------------------------------------------------//
    //Employee Dependents
    Route::resource('{lang}/employees.dependents', 'EmployeeDependentsController');
    //Education
    Route::get('{lang}/education', 'EducationController@index');
    Route::post('{lang}/education/store', 'EducationController@store');
    Route::post('{lang}/education/update/{id}', 'EducationController@update');
    Route::post('{lang}/education/destroy/{id}', 'EducationController@destroy');
    //Employement Status
    Route::resource('{lang}/employees.employment-status', 'EmployeeEmploymentStatusController');
    //Job
    Route::resource('{lang}/employees.jobs', 'EmployeeJobController');

    //Visa
    Route::get('{lang}/visa', 'EmployeeVisaController@index');
    Route::post('{lang}/visa/store', 'EmployeeVisaController@store');
    Route::post('{lang}/visa/update/{id}', 'EmployeeVisaController@update');
    Route::post('{lang}/visa/destroy/{id}', 'EmployeeVisaController@destroy');

    //Employee Benefit details
    Route::resource('{lang}/employees.benefit-details', 'EmployeeBenefitController');
    //Employee Benefit Status
    Route::get('{lang}/employee/benefit-status/{employeeID_GoupPlanID_Status}', 'EmployeeBenefitController@create');
    Route::post('{lang}/employee/benefit-status/{employeeID_GoupPlanID_Status}/store', 'EmployeeBenefitController@store');

    //Task
    Route::resource('{lang}/employees/{employeeID}/task', 'EmployeeTaskController');
    Route::post('mytask/completedstatus', 'EmployeeTaskController@completedstatusAJAX');
    Route::post('mytask/incompletedstatus', 'EmployeeTaskController@incompletedstatusAJAX');

    //Employee_Time_off
    Route::group(['prefix' => '{lang}/employee/{employee_id}'], function () {
        //Accrual Options
        Route::post('timeoff/save-accrual-option', 'EmployeeTimeOffController@saveAccrualOption')->name('timeoff.save-accrual-option');
        Route::get('timeoff/change-accrual-policy', 'EmployeeTimeOffController@changePolicy')->name('timeoff.change-policy');
        
        //Update Upcoming time off in Employee_Time_off
        Route::post('timeoff/update', 'EmployeeTimeOffController@updateTimeOff')->name('timeoff.update-time-off');
        
        //TODO:: Change check according to Approval Service
        //Approve Upcoming time off in Employee_Time_off
        Route::post('timeoff/approve-request/{id}', 'EmployeeTimeOffController@approveRequestTimeOff')->name('timeoff.approve-request');
        //Deny Upcoming time off in Employee_Time_off
        Route::post('timeoff/deny-request/{id}', 'EmployeeTimeOffController@denyTimeOff')->name('timeoff.deny-request');
        //Cancel time off in Employee_Time_off
        Route::post('timeoff/cancel-request', 'EmployeeTimeOffController@cancelTimeOff')->name('timeoff.cancel-request');

        ///Adjust Balance
        Route::post('timeoff/adjust-balance', 'EmployeeTimeOffController@adjustBalanceManually')->name('timeoff.adjust-balance');
    
        //history table route
        Route::get('timeoff/history', 'EmployeeTimeOffController@filterHistory')->name('timeoff.history');
        Route::get('timeoff/calculate', 'EmployeeTimeOffController@calculateTimeOff')->name('timeoff.calculate');
        Route::get('timeoff/policiestype', 'EmployeeTimeOffController@policiestype')->name('timeoff.policies-type');

        Route::resource('timeoff', 'EmployeeTimeOffController');
    });
    
    //Employees
    Route::Get('{lang}/employees/{id?}', [
        'uses' => 'EmployeeController@index',
        'as' => 'employees',
    ]);
    Route::Get('/all-employees', [
        'uses' => 'EmployeeController@all_employees',
        'as' => 'all_employees',
    ]);
    Route::Get('{lang}/employee/create', [
        'uses' => 'EmployeeController@create',
        'as' => 'employee.create',
    ])->middleware('has_full_permission:manage employees store');
    Route::Post('{lang}/employee/store', [
        'uses' => 'EmployeeController@store',
        'as' => 'employee.store',
    ])->middleware('has_full_permission:manage employees store');
    Route::get('{lang}/employee/edit/{id}', [
        'uses' => 'EmployeeController@edit',
        'as' => 'employee.edit',
    ]);

    //trash
    Route::Get('/employee/trashed', [
        'uses' => 'EmployeeController@trashed',
        'as' => 'employee.trashed',
    ]);

    Route::Get('/employee/kill/{id}', [
        'uses' => 'EmployeeController@kill',
        'as' => 'employee.kill',
    ]);
    Route::Get('/employee/restore/{id}', [
        'uses' => 'EmployeeController@restore',
        'as' => 'employee.restore',
    ]);

    //Delete Employee
    Route::Post('/employee/delete/{id}', [
        'uses' => 'EmployeeController@destroy',
        'as' => 'employee.destroy',
    ]);

    //Employee Documents
    Route::resource('{lang}/employees/{id}/docs', 'EmployeeDocumentController');
    //---------------------------------------------------------------------------------//

    //TODO:: Need to remove these Routes - not being used in our system
    //--------------------------------------------------------------------------------------------------//

    //AttendanceController
    Route::post('/slackbot', 'AttendanceController@newSlackbot')->name('slackbot');
    //attendance
    Route::Get('/attendance/show/{id?}', [
        'uses' => 'AttendanceController@showAttendance', //show Attendance
        'as' => 'attendance',
    ]);
    //attendance
    Route::Get('{lang}/attendance/timeline/{id?}', [
        'uses' => 'AttendanceController@showTimeline', //show Attendance
        'as' => 'timeline',
    ]);

    Route::Get('{lang}/attendance/today-timeline/{id?}', [
        'uses' => 'AttendanceController@todayTimeline', //show Attendance
        'as' => 'today_timeline',
    ]);

    Route::Get('/attendance/create/{id?}/{date?}/', [
        'uses' => 'AttendanceController@create', //show Attendance
        'as' => 'attendance.create',
    ]);
    //Attendance and leave check for ajax for shown in update form
    Route::Get('/attendance/getbyAjax', [
        'uses' => 'AttendanceController@getbyAjax',
        'as' => 'attendance.showByAjax',
    ]);

    Route::Get('/attendance/edit/{id}', [
        'uses' => 'AttendanceController@edit',
        'as' => 'attendance.edit',
    ]);

    Route::Post('/attendance/storeAttendanceSummaryToday', [
        'uses' => 'AttendanceController@storeAttendanceSummaryToday',
        'as' => 'attendance.storeAttendanceSummaryToday',
    ]);
    Route::Post('/attendance/store', [
        'uses' => 'AttendanceController@store',
        'as' => 'attendance.store',
    ]);
    Route::Post('{lang}/attendance/store-break', [
        'uses' => 'AttendanceController@storeBreak',
        'as' => 'attendance.storeBreak',
    ]);
    Route::Get('/attendance/show/{id}', [
        'uses' => 'AttendanceController@index',
        'as' => 'attendance.show',
    ]);
    Route::Post('/attendance/delete', [
        'uses' => 'AttendanceController@destroy',
        'as' => 'attendance.destroy',
    ]);
    Route::Post('/attendance/deletechecktime', [
        'uses' => 'AttendanceController@deleteChecktime',
        'as' => 'attendance.deletechecktime',
    ]);

    Route::Post('/attendance/update', [
        'uses' => 'AttendanceController@update',
        'as' => 'attendance.update',
    ]);
    Route::Get('{lang}/attendance/create-break/{id?}/{date?}/', [
        'uses' => 'AttendanceController@createBreak', //show Attendance
        'as' => 'attendance.createBreak',
    ]);

    //Attendance Break
    Route::Post('/attendance/deletebreakchecktime', [
        'uses' => 'AttendanceController@deleteBreakChecktime',
        'as' => 'attendance.deleteBreakChecktime',
    ]);

    Route::Post('{lang}/attendance/update-break', [
        'uses' => 'AttendanceController@updateBreak',
        'as' => 'attendance.updateBreak',
    ]);
    // My Attendance
    Route::GET('{lang}/attendance/myAttendance/{id?}', [
        'uses' => 'AttendanceController@authUserTimeline',
        'as' => 'myAttendance',
    ]);
    Route::post('{lang}/attendance/correction-email', [
        'uses' => 'AttendanceController@correctionEmail',
        'as' => 'correction_email',
    ]);
    Route::Post('{lang}/attendance/delete/{id}', [
        'uses' => 'AttendanceController@attendanceSummaryDelete',
        'as' => 'attendance.delete',
    ]);

    //Personal Profile
    Route::Get('{lang}/personal-profile/', [
        'uses' => 'ProfileController@index',
        'as' => 'profile.index',
    ]);
    Route::Get('{lang}/personal-profile/edit', [
        'uses' => 'ProfileController@edit',
        'as' => 'profile.edit',
    ]);
    Route::POST('{lang}/personal-profile/update', [
        'uses' => 'ProfileController@update',
        'as' => 'profile.update',
    ]);

    //Leave Types
    Route::Get('{lang}/leave-types', [
        'uses' => 'LeaveTypeController@index',
        'as' => 'leave_type.index',
    ]);
    Route::post('{lang}/leave-type/create', [
        'uses' => 'LeaveTypeController@create',
        'as' => 'leave_type.create',
    ]);
    Route::post('{lang}/leave-type/update/{id}', [
        'uses' => 'LeaveTypeController@update',
        'as' => 'leave_type.update',
    ]);

    Route::post('{lang}/leave-type/delete/{id}', [
        'uses' => 'LeaveTypeController@delete',
        'as' => 'leave_type.delete',
    ]);

    //edit
    Route::get('/profile', [
        'uses' => 'EmployeeController@profile',
        'as' => 'employee.profile',
    ]);
    Route::Post('{lang}/employee/update/{id}', [
        'uses' => 'EmployeeController@update',
        'as' => 'employee.update',
    ]);
    Route::Post('{lang}/employee/update/{id}/salary', [
        'uses' => 'EmployeeController@updateSalary',
        'as' => 'employee.update.salary',
    ]);
    Route::resources([
        '{lang}/employee-contact-info' => 'EmployeeContactInformationController',
    ]);

    //Salary
    Route::Get('{lang}/salary/{id?}', [
        'uses' => 'SalariesController@index',
        'as' => 'salary.show',
    ]);
    //add Bonus
    Route::Post('{lang}/salary/addBonus/{id}', [
        'uses' => 'SalariesController@addBonus',
        'as' => 'salary.bonus',
    ]);

    //proccessed

    Route::Post('/salary/process', [
        'uses' => 'SalariesController@processSalary',
        'as' => 'salary.processed',
    ]);
    //Allowances
    Route::post('{lang}/allowance/add', [
        'uses' => 'SalariesController@addAllowance',
        'as' => 'allowance.add',
    ]);
    Route::post('{lang}/allowance/update/{id}', [
        'uses' => 'SalariesController@updateAllowance',
        'as' => 'allowance.update',
    ]);
    Route::post('{lang}/allowance/delete/{id}', [
        'uses' => 'SalariesController@deleteAllowance',
        'as' => 'allowance.delete',
    ]);
    //Deduction
    Route::post('{lang}/deduction/add', [
        'uses' => 'SalariesController@addDeduction',
        'as' => 'deduction.add',
    ]);
    Route::post('{lang}/deduction/update/{id}', [
        'uses' => 'SalariesController@updateDeduction',
        'as' => 'deduction.update',
    ]);
    Route::post('/deduction/delete/{id}', [
        'uses' => 'SalariesController@deleteDeduction',
        'as' => 'deduction.delete',
    ]);
    Route::get('{lang}/salary-templates/', [
        'uses' => 'SalariesController@salaryTemplates',
        'as' => 'salary_templates',
    ]);
    Route::post('/salary-template/add', [
        'uses' => 'SalariesController@addSalaryTemplates',
        'as' => 'salary_template.add',
    ]);
    Route::post('{lang}/salary-template/update/{id}', [
        'uses' => 'SalariesController@updateSalaryTemplates',
        'as' => 'salary_template.update',
    ]);
    Route::post('{lang}/salary-template/delete/{id}', [
        'uses' => 'SalariesController@deleteSalaryTemplates',
        'as' => 'salary_template.delete',
    ]);
    Route::get('{lang}/manage-salary-template/{id}', [
        'uses' => 'SalariesController@manageSalaryTemplate',
        'as' => 'manage_salary_template',
    ]);
    Route::get('{lang}/manage-salary', [
        'uses' => 'SalariesController@manageSalary',
        'as' => 'manage_salary',
    ]);
    Route::post('/assign_template', [
        'uses' => 'SalariesController@assignTemplate',
        'as' => 'assign_template',
    ]);
    Route::POST('{lang}/slip', [
        'uses' => 'SalariesController@salarySlip',
        'as' => 'slip',
    ]);

    Route::GET('{lang}/generate-salary-slip', [
        'uses' => 'SalariesController@generateSalarySlip',
        'as' => 'generate-slip',
    ]);
    Route::GET('{lang}/generate-personal-salary-slip', [
        'uses' => 'SalariesController@generatePersonalSalarySlip',
        'as' => 'generate-personal-slip',
    ]);
    //Leaves
    Route::Get('{lang}/employee-leaves/{id?}', [
        'uses' => 'LeaveController@employeeLeaves',
        'as' => 'employeeleaves',
    ]);
    Route::Get('{lang}/leave/edit/{id}', [
        'uses' => 'LeaveController@edit',
        'as' => 'leave.edit',
    ]);
    Route::Get('{lang}/leave/show/{id}', [
        'uses' => 'LeaveController@show',
        'as' => 'leave.show',
    ]);
    Route::Post('{lang}/leave/update/{id}', [
        'uses' => 'LeaveController@update',
        'as' => 'leave.update',
    ]);
    Route::Get('{lang}/leave/updateStatus/{id}/{status}', [
        'uses' => 'LeaveController@updateStatus',
        'as' => 'leave.updateStatus',
    ]);
    Route::Post('{lang}/leave/delete/{id}', [
        'uses' => 'LeaveController@leaveDelete',
        'as' => 'leave.delete',
    ]);
    Route::Get('{lang}/my-leaves', [
        'uses' => 'LeaveController@index',
        'as' => 'leave.index',
    ]);
    Route::Get('{lang}/leave/create', [
        'uses' => 'LeaveController@create',
        'as' => 'leaves',
    ]);
    Route::Get('{lang}/leave/admin-create/{id?}', [
        'uses' => 'LeaveController@adminCreate',
        'as' => 'admin.createLeave',
    ]);
    Route::Post('{lang}/leave/store', [
        'uses' => 'LeaveController@store',
        'as' => 'leaves.store',
    ]);
    Route::Post('{lang}/leave/admin-store', [
        'uses' => 'LeaveController@adminStore',
        'as' => 'leaves.adminStore',
    ]);

    //-----------------------------------------------------------------------------------//

    //For All Employees
    //dashboard
    Route::get('{lang}/dashboard', [
        'uses' => 'DashboardController@index',
        'as' => 'admin.dashboard',
    ]);
    Route::get('{lang}/help', [
        'uses' => 'DashboardController@help',
        'as' => 'admin.help',
    ]);
    Route::post('{lang}/contact-us', [
        'uses' => 'DashboardController@contactUs',
        'as' => 'contact_us',
    ]);
    //organization-hierarchy
    Route::resources([
        '/organization-hierarchy' => 'OrganizationHierarchyController',
    ]);
    //Inbox
    Route::get('{lang}/inbox/{trash?}', 'InboxController@index')->name('inbox.index.trash');
    Route::get('{lang}/inbox/show/{inbox}', 'InboxController@show')->name('inbox.show');
    Route::get('{lang}/inbox/restore/{inbox}', 'InboxController@restore')->name('inbox.restore');
    Route::get('{lang}/inbox/completed/notification', 'InboxController@completed')->name('inbox.completed');
    Route::resource('{lang}/inbox', 'InboxController');
    Route::get('{lang}/read-all-notifications', 'InboxController@readAllNotifications');
    //Change Approval Request
    //FIXME:: need to decide the correct location of these routes
    Route::resource('{lang}/employees.request-change', 'RequestChangeApprovalController');
    Route::post('{lang}/employees.approval', 'RequestChangeApprovalController@store');

    //Temporary Route for Approve Request Testing
    // Route::post('{lang}/inbox/approval-requests/{request}/{approve}', 'RequestLifeCycleController@update');
    // Route::resource('{lang}/inbox/approval-requests.approve', 'RequestLifeCycleController');
    Route::post('/approval-requests/{requestedDataID}/approve/{status}', 'RequestLifeCycleController@requestStatusChange');

    Route::group(['prefix' => '{lang}/handbook'], function () {

        Route::resource('chapter', 'ChapterController')->middleware('has_full_permission:manage company handbook,index');

        Route::post('chapter/{chapter_id}/page/{page_id}/destroy', 'PageController@destroyPageWithChapter')->name('chapter.page.destroyPageWithChapter')->middleware('has_full_permission:manage company handbook');

        Route::resource('chapter.page', 'PageController')->middleware('has_full_permission:manage company handbook,show');
    });

    Route::group(['prefix' => '{lang}/employee'], function () {
        Route::resource('import', 'ImportEmployeeController', [
            'names' => [
                'create' => 'import.employee.create',
                'store' => 'import.employee.store',
                'show' => 'import.employee.show',
            ]
        ])->middleware('has_full_permission:manage employees store');
    });

    Route::group(['prefix' => '{lang}/performance-review'], function () {

        //Performance Questions Controller Route
        Route::resource('questions', 'PerformanceQuestionController')->middleware('has_full_permission:manage performance review');

        // Performance Questionnaire Controller Routes
        Route::get('forms/{questionnaire_form_id}/assign', 'PerformanceFormController@assign')->name('forms.assign')->middleware('has_full_permission:manage performance review');

        Route::post('forms/{questionnaire_form_id}/assigned', 'PerformanceFormController@submitAssignment')->name('forms.submitAssignment')->middleware('has_full_permission:manage performance review');

        Route::resource('forms', 'PerformanceFormController')->middleware('has_full_permission:manage performance review');

        //Employee Performance Review Controller Routes

        Route::get('evaluations/employee/{employee_id}', 'EmployeePerformanceEvaluationController@employeeEvaluations')->name('evaluations.employee-evaluations');

        Route::get('evaluations/employee/{employee_id}/evaluation/{evaluation_id}/view', 'EmployeePerformanceEvaluationController@view')->name('evaluations.view');

        Route::get('evaluations/employee/{employee_id}/evaluation/{evaluation_id}/edit', 'EmployeePerformanceEvaluationController@edit')->name('evaluations.employee.edit');

        Route::get('evaluations/employee/{employee_id}/evaluation/{evaluation_id}/decision', 'EmployeePerformanceEvaluationController@decision')->name('evaluations.decision')->middleware('has_full_permission:manage performance review decision');

        Route::post('evaluations/employee/{employee_id}/evaluation/{evaluation_id}/submit-decision', 'EmployeePerformanceEvaluationController@submitDecision')->name('evaluations.submitDecision')->middleware('has_full_permission:manage performance review decision');

        Route::get('evaluations/employees/assign', 'EmployeePerformanceEvaluationController@assign')->name('evaluations.assign')->middleware('has_full_permission:manage performance review assign');

        Route::post('evaluations/employees/assigned', 'EmployeePerformanceEvaluationController@submitAssignment')->name('evaluations.submitAssignment')->middleware('has_full_permission:manage performance review assign');

        Route::get('evaluations/employee/{employee_id}/fill', 'EmployeePerformanceEvaluationController@fill')->name('evaluations.fill')->middleware('has_full_permission:manage performance review');

        Route::resource('evaluations', 'EmployeePerformanceEvaluationController')->middleware('has_full_permission:manage performance review,index|show');
    });

    Route::group(['prefix' => '{lang}/attendance'], function () {
        Route::get('import/create/{id}', ['as' => 'import.attendance.create', 'uses' => 'ImportEmployeeAttendanceController@create']);
        Route::resource('import', 'ImportEmployeeAttendanceController', [
            'names' => [
                'create' => 'import.attendance.create',
                'store' => 'import.attendance.store',
                'show' => 'import.attendance.show',
            ],
            ['except' => ['create']]
        ])->middleware('has_full_permission:manage employees attendance');
    });

    Route::get('{lang}/attendance-management/validateWorkSchedule', 'AttendanceManagementController@validateWorkSchedule')->name('attendance-management.validateWorkSchedule')->middleware('has_full_permission:manage employees attendance');
    Route::get('{lang}/employees/{employee_id}/employee-attendance/{date}/history', 'EmployeeAttendanceController@history')->name('employees.employee-attendance.history')->middleware('has_full_permission:manage employees attendance');
    Route::resource('{lang}/employees.employee-attendance.attendance-management', 'AttendanceManagementController')->middleware('has_full_permission:manage employees attendance');
    Route::get('{lang}/attendance-management/{id}/history', 'AttendanceManagementController@history')->name('attendance-management.history')->middleware('has_full_permission:manage employees attendance');
    Route::resource('{lang}/attendance-management', 'AttendanceManagementController')->middleware('has_full_permission:manage employees attendance');
    Route::get('/attendance-management/{id}/show', 'AttendanceManagementController@show')->middleware('has_full_permission:manage employees attendance');
    Route::post('{lang}/attendance-management/import/preview', 'AttendanceManagementController@preview')->middleware('has_full_permission:manage employees attendance');
    Route::get('{lang}/getAttendance', 'AttendanceManagementController@getAttendance');

    Route::post('{lang}/pay-schedule/updatePayDate', 'PayScheduleController@updatePayDateByAJAX')->name('pay-schedule.update-pay-date')->middleware('has_full_permission:manage pay schedule');
    Route::post('{lang}/pay-schedule/getPayDates', 'PayScheduleController@getPayDatesByAJAX')->name('pay-schedule.get-pay-dates')->middleware('has_full_permission:manage pay schedule');
    Route::get('{lang}/pay-schedule/{id}/assign', 'PayScheduleController@assign')->name('pay-schedule.assign')->middleware('has_full_permission:manage pay schedule');
    Route::post('{lang}/pay-schedule/{id}/assigned', 'PayScheduleController@submitAssignment')->name('pay-schedule.submitAssignment')->middleware('has_full_permission:manage pay schedule');
    Route::resource('{lang}/pay-schedule', 'PayScheduleController')->middleware('has_full_permission:manage pay schedule');
    Route::resource('{lang}/delete-monthly-attendance', 'DeleteMonthlyAttendanceController')->middleware('has_full_permission:manage employees attendance');

    Route::resource('{lang}/employees.employee-attendance.correction-requests', 'EmployeeAttendanceCorrectionController')->middleware('has_full_permission:manage employees attendance,index|create|store');

    // Correction Request
    Route::resource('{lang}/correction-requests', 'EmployeeAttendanceCorrectionController')->middleware('has_full_permission:manage employees attendance,index|edit|update');
    Route::resource('{lang}/correction-requests.decision', 'EmployeeAttendanceCorrectionDecisionController')->middleware('has_full_permission:manage employees attendance');

    // Holidays
    Route::group(['prefix' => '{lang}/'], function () {
        Route::get('holidays/filter', 'HolidayController@filterHolidays');
        Route::resource('holidays', 'HolidayController')->middleware('has_full_permission:manage company holidays,index');
    });
    
    Route::resource('{lang}/smtp-details', 'SmtpDetailController')->middleware('has_full_permission:manage setting smtp details');
    Route::resource('{lang}/smtp-details.status', 'SmtpDetailStatusController')->middleware('has_full_permission:manage setting smtp details');

    Route::resource('{lang}/compensation-change-reasons', 'CompensationChangeReasonController')->middleware('has_full_permission:manage setting compensation');

    Route::resource('employee.compensations', 'EmployeeCompensationController')->middleware('has_full_permission:manage setting compensation');
});
$languages = [];


/* FIXME: DB checks shouldn't be in here
if (Schema::hasTable('languages')) {
    $language_name = DB::table('languages')->select('short_name')->get();
    foreach ($language_name as $key => $value) {
        array_push($languages, $value->short_name);
    }
} else {
    $languages = array('en', 'es');
} */

$languages = ['en', 'es'];

foreach ($languages as $lang) {
    //
    //Noes
    Route::get($lang . '/employee/notes/{id}', [
        'uses' => 'NotesController@index',
        'as' => 'employee.notes',
    ]);
    Route::post($lang . '/notes/create', [
        'uses' => 'NotesController@store',
        'as' => 'employee.notesCreate',
    ]);
    Route::post($lang . '/notes/update/{id}', [
        'uses' => 'NotesController@update',
        'as' => 'employee.notesUpdate',
    ]);
    Route::post($lang . '/notes/delete/{id}', [
        'uses' => 'NotesController@destroy',
        'as' => 'employee.notesDelete',
    ]);

    //   Import Data
    Route::get($lang . '/importdata', [
        'uses' => 'DashboardController@importdata',
        'as' => 'admin.importdata',
    ]);
    Route::post($lang . '/import', [
        'uses' => 'DashboardController@import',
        'as' => 'admin.import',
    ]);
    Route::Post($lang . '/leave/delete/{id}', [
        'uses' => 'LeaveController@destroy',
        'as' => 'leave.destroy',
    ]);
    Route::Get('{lang}/employees/{id?}', [
        'uses' => 'EmployeeController@index',
        'as' => 'employees',
    ]);
    Route::Get($lang . '/leave/create', [
        'uses' => 'LeaveController@create',
        'as' => 'leaves',
    ]);
    Route::resources([
        $lang . '/organization-hierarchy' => 'OrganizationHierarchyController',
    ]);
    Route::get($lang . '/documents/edit/{id}', [
        'as' => 'documents.edit',
        'uses' => 'DocumentsController@editDocument',
    ]);
    Route::post($lang . '/salary-template/add', [
        'uses' => 'SalariesController@addSalaryTemplates',
        'as' => 'salary_template.add',
    ]);
    Route::resources([
        $lang . '/locations' => 'LocationsController',
    ]);
    Route::resources([
        $lang . '/job' => 'JobOpeningController',
    ]);
    Route::resources([
        $lang . '/email-templates' => 'EmailTemplateController',
    ]);
    Route::get('/setEmail', 'EmailTemplateController@setWelcomeEmail');
}

// Poll
Route::group(['prefix' => '{lang}/'], function () {
    Route::resource('polls', 'PollController')->middleware('has_full_permission:manage poll,index');
    Route::resource('polls.questions', 'PollQuestionController')->middleware('has_full_permission:manage poll');
    Route::resource('polls.take', 'PollAnswerController');
});

// contact us
Route::resource('{lang}/contact-us', 'ContactUSController');

