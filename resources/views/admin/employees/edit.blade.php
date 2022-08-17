@extends('layouts/contentLayoutMaster')
@section('title','Edit Employee')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <div class="card card-primary card-outline">
        <!--begin: Form Tabs Form-->

        <div class="card-body">
            @php
                $tabCount=0;
            @endphp
            <ul class="nav nav-pills nav-pills-margin-bottom" id="custom-content-tab" role="tablist">
                @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee firstname'])
                || isset($perms['employee'][$employee->id]['employee lastname'])
                || isset($perms['employee'][$employee->id]['employee personal_email'])
                || isset($perms['employee'][$employee->id]['employee official_email'])
                || isset($perms['employee'][$employee->id]['employee designation'])
                || isset($perms['employee'][$employee->id]['employee employment_status'])
                || isset($perms['department'][$employee->id]['department name'])
                || isset($perms['location'][$employee->id]['location name'])
                || isset($perms['employee'][$employee->id]['employee gender'])
                || isset($perms['employee'][$employee->id]['employee marital_status'])
                || isset($perms['employee'][$employee->id]['employee date_of_birth'])
                )
                    @php
                        $tabCount++;
                        if($tabCount==1)
                        $personal=true;
                    @endphp
                    <li class="nav-item ">
                        <a class="nav-link d-flex align-items-center active" @if($tabCount == 1) active @endif"
                        id="custom-content-home-tab" data-toggle="pill" href="#custom-content-home" role="tab"
                        aria-controls="custom-content-home"
                        aria-selected="true"> <i data-feather="user"></i><span class="d-none d-sm-block">
                        {{__('language.Personal')}}</span></a>
                    </li>
                @endif
                @if(Auth::user()->isAdmin()
                || isset($perms['employee'][$employee->id]['employee contact_no'])
                || isset($perms['employee'][$employee->id]['employee nin'])
                || isset($perms['employee'][$employee->id]['employee emergency_contact'])
                || isset($perms['employee'][$employee->id]['employee emergency_contact_relationship'])
                || isset($perms['employee'][$employee->id]['employee current_address'])
                || isset($perms['employee'][$employee->id]['employee parmanent_address'])
                || isset($perms['employee'][$employee->id]['employee city']))
                    @php
                        $tabCount++;
                        if($tabCount==1)
                        $contact=true;
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link  @if($tabCount == 1) active @endif" id="custom-content-profile-tab"
                           data-toggle="pill" href="#custom-content-profile" role="tab"
                           aria-controls="custom-content-profile" aria-selected="false"><i
                                    data-feather='alert-circle'></i><span
                                    class="d-none d-sm-block">{{__('language.Contact')}}</span></a>
                    </li>
                @endif

            <!--------Job Information --- TODO::Chnage + add permissions check----->
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob effective_date'])
                    || isset($perms['job'][$employee->id]['employeejob designation_id'])
                    || isset($perms['job'][$employee->id]['employeejob department_id'])
                    || isset($perms['job'][$employee->id]['employeejob location_id'])
                    || isset($perms['job'][$employee->id]['employeejob report_to']))
                    @php
                        $tabCount++;
                        if($tabCount==1)
                        $job=true;
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link  @if($tabCount == 1) active @endif" id="custom-content-job-tab"
                           data-toggle="pill" href="#custom-content-job" role="tab" aria-controls="custom-content-job"
                           aria-selected="false"><i data-feather="briefcase"></i><span
                                    class="d-none d-sm-block"> {{__('language.Job')}}</span></a>
                    </li>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]) || isset($perms['educationType'][$employee->id]) || isset($perms['secondaryLanguage'][$employee->id]))
                    @php
                        $tabCount++;
                        if($tabCount==1)
                        $education=true;
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link  @if($tabCount == 1) active @endif " id="custom-education-tab"
                           data-toggle="tab" href="#custom-education" role="tab" aria-controls="custom-education-tab"
                           aria-selected="false"><i data-feather='book-open'></i><span
                                    class="d-none d-sm-block">{{__('language.Education Details')}}</span></a>
                    </li>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['visaType'][$employee->id]) || isset($perms['employeeVisa'][$employee->id]))
                    @php
                        $tabCount++;
                        if($tabCount==1)
                        $visa=true;
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link  @if($tabCount == 1) active @endif" id="custom-visa-tab" data-toggle="pill"
                           href="#custom-visa" role="tab" aria-controls="custom-visa-tab"
                           aria-selected="false"><i data-feather='globe'></i><span
                                    class="d-none d-sm-block">{{__('language.Visa Details')}}</span></a>
                    </li>
                @endif
                @if(isset($perms['employeeAccessLevel']['all']) && array_intersect(['manage setting employee_accesslevel'], $perms['employeeAccessLevel']['all'] ))
                    @if(isset($roles))
                        @php
                            $tabCount++;
                            if($tabCount==1)
                            $role=true;
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link  @if($tabCount == 1) active @endif" id="custom-change-role-tab"
                               data-toggle="pill" href="#custom-change-role" role="tab"
                               aria-controls="custom-change-role-tab"
                               aria-selected="false">{{__('language.Change')}} {{__('language.Employee')}} {{__('language.Role')}}</a>
                        </li>
                    @endif
                @endif
                @if($requestApprovals->isNotEmpty())
                    @php
                        $tabCount++;
                        if($tabCount==1)
                        $approval=true;
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link @if($tabCount == 1) active @endif" id="custom-request-change-tab"
                           data-toggle="pill" href="#request-change-tab" role="tab"
                           aria-controls="custom-request-change"
                           aria-selected="false">{{__('language.Request a change')}}</a>
                    </li>
                @endif
            </ul>
            <div class="row">
            <!-- <div class="col-md-12 text-center">
    @if($employee->picture != '')
                <input type="image" src="{{asset($employee->picture)}}" class="img-circle img-bordered attachment-block" alt="Employee Picture" id="wizardPicturePreview" title="" width="auto"
        @if(Auth::user()->isAdmin() || isset($perms['employee']['all']) && array_intersect(['manage employees change_photos'], $perms['employee']['all'] ))
                    onclick="document.getElementById('wizard-picture').click();"
@endif
                        height="150"/>
@else
                <input type="image" src="{{asset('asset/media/error/default.png')}}" class="img-circle img-bordered attachment-block" id="wizardPicturePreview" title="" width="auto" height="150"
        @if(Auth::user()->isAdmin() || isset($perms['employee']['all']) && array_intersect(['manage employees change_photos'], $perms['employee']['all'] ))
                    onclick="document.getElementById('wizard-picture').click();"
@endif/>
    @endif

                    <small id="emailHelp" class="form-text text-muted">Click on Image to Add Photo.</small>

                </div> -->
            </div>
            <div class="tab-content" id="custom-content-tabContent">

                <!----begin: Tabs--->

                <div class="tab-pane fade @if(isset($personal)) show active @endif" id="custom-content-home"
                     role="tabpanel" aria-labelledby="custom-content-home-tab">
                    <form class="form" id="edit-employee"
                          action="@if(isset($locale)){{url($locale.'/employee/update',$employee->id)}} @else {{url('en/employee/update',$employee->id)}} @endif"
                          method="post"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="media mb-2">
                            @if($employee->picture != '')
                                <img
                                        src="{{asset($employee->picture)}}"
                                        id="wizardPicturePreview"
                                        alt="users avatar"
                                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                        height="90"
                                        width="90"
                                @if(Auth::user()->isAdmin() || isset($perms['employee']['all']) && array_intersect(['manage employees change_photos'], $perms['employee']['all'] ))
                                        @endif
                                />
                            @else
                                <img
                                        src="{{asset('asset/media/users/default3.png')}}"
                                        id="wizardPicturePreview"
                                        alt="users avatar"
                                        class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                        height="90"
                                        width="90"
                                @if(Auth::user()->isAdmin() || isset($perms['employee']['all']) && array_intersect(['manage employees change_photos'], $perms['employee']['all'] ))
                                        @endif
                                />
                            @endif
                            <div class="media-body mt-50">
                                <h4>{{$employee->firstname}} {{$employee->lastname}}</h4>
                                <div class="col-12 d-flex mt-1 px-0">
                                    <label class="btn btn-primary mr-75 mb-0" for="picture">
                                        <span class="d-none d-sm-block">Change</span>
                                        <input class="form-control" type="file" name="picture" id="picture" hidden
                                               accept="image/png, image/jpeg, image/jpg">
                                        <span class="d-block d-sm-none">
                        <i class="mr-0" data-feather="edit"></i>
                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom mb-1">
                            <div class="head-label">
                                <h4><i class="font-medium-3 mr-25"
                                       data-feather="user"></i>{{__('language.Personal')}} {{__('language.Information')}}
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            @if(Auth::user()->isAdmin() || isset($perms['employee']['all']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Employee')}} #</label>
                                        <input type="text" name="employee_no" value="{{ old('employee_no', $employee->employee_no) }}" 
                                        class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Employee')}} {{__('language.Number')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Employee')}} #</label>
                                        <input type="text" name="employee_no" value="{{ old('employee_no', $employee->employee_no) }}" 
                                        class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Employee')}} {{__('language.Number')}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee firstname']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.First Name')}}</label>
                                        <input type="text" name="firstname"
                                               value="{{old('firstname', $employee->firstname)}}" class="form-control"
                                               placeholder="{{__('language.Enter')}} {{__('language.First Name')}}"
                                               @if(in_array('firstname',$disableFields)) readonly @endif
                                               @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee firstname'])
                                           && $perms['employee'][$employee->id]['employee firstname']=="view") readonly @endif>
                                    </div>
                                </div>
                            @endif
                        <!--/span-->
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee lastname']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Last Name')}}</label>
                                        <input type="text" name="lastname"
                                               value="{{old('lastname',$employee->lastname)}}" class="form-control "
                                               placeholder="{{__('language.Enter')}} {{__('language.Last Name')}}"
                                               @if(in_array('lastname',$disableFields)) readonly @endif
                                               @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee lastname'])
                                           && $perms['employee'][$employee->id]['employee lastname']=="view") readonly @endif>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!--/row-->
                        <div class="row">
                            <!--/span-->
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee official_email']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Official')}} {{__('language.Email')}}</label>
                                        <input type="email" name="official_email"
                                               value="{{old('official_email',$employee->official_email)}}"
                                               class="form-control "
                                               placeholder="{{__('language.Enter')}} {{__('language.Official')}} {{__('language.Email')}}"
                                               @if(in_array('official_email',$disableFields)) disabled
                                               @endif @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee official_email'])
                    && $perms['employee'][$employee->id]['employee official_email']=="view") disabled @endif>
                                    </div>
                                </div>
                            @endif
                        <!--/span-->
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee personal_email']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Personal')}} {{__('language.Email')}}</label>
                                        <input type="email" name="personal_email"
                                               value="{{old('personal_email',$employee->personal_email)}}"
                                               class="form-control "
                                               placeholder="{{__('language.Enter')}} {{__('language.Personal')}} {{__('language.Email')}}"
                                               @if(in_array('personal_email',$disableFields)) disabled
                                               @endif @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee personal_email'])
                    && $perms['employee'][$employee->id]['employee personal_email']=="view") disabled @endif>
                                    </div>
                                </div>
                        @endif
                        <!--/span-->

                        </div>

                        <div class="row">

                            <!--/span-->
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee date_of_birth']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right"
                                               for="date_of_birth">{{__('language.Date of Birth')}}</label>
                                        <input type="date" class="form-control " placeholder="1988-12-23"
                                               name="date_of_birth" id="date_of_birth"
                                               value="{{old('date_of_birth',$employee->date_of_birth)}}"
                                               @if(in_array('date_of_birth',$disableFields)) disabled @endif @if(Auth::user()->isNotAdmin()
                        && isset($perms['employee'][$employee->id]['employee date_of_birth']) && $perms['employee'][$employee->id]['employee date_of_birth']=="view") disabled @endif>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee gender']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Gender')}}</label>
                                        <select class="form-control custom-select" data-placeholder="Select Gender"
                                                tabindex="1" name="gender"
                                                @if(in_array('gender',$disableFields)) disabled @endif
                                                @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee gender']) && $perms['employee'][$employee->id]['employee gender']=="view")
                                                disabled="true" @endif>
                                            <option value="">{{__('language.Select')}} {{__('language.Gender')}}</option>
                                            <option value="Male" @if($employee->gender=="Male") selected @endif >Male
                                            </option>
                                            <option value="Female" @if($employee->gender=="Female") selected @endif>
                                                Female
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee marital_status']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Marital Status')}}</label>
                                        <select class="form-control custom-select"
                                                data-placeholder="Select Marital Status" tabindex="1"
                                                name="marital_status"
                                                @if(in_array('marital_status',$disableFields)) disabled @endif
                                                @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee marital_status']) && $perms['employee'][$employee->id]['employee marital_status']=="view")
                                                disabled="true" @endif>
                                            <option value="">{{__('language.Select')}} {{__('language.Marital Status')}}</option>
                                            <option value="Single"
                                                    @if($employee->marital_status=="Single") selected @endif >
                                                Single
                                            </option>
                                            <option value="Married"
                                                    @if($employee->marital_status=="Married") selected @endif>
                                                Married
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-1 mt-2">
                            <div class="head-label">
                            <!-- <h4><i class="font-medium-3 mr-25" data-feather='briefcase'></i>{{__('language.Job')}} {{__('language.Information')}}</h4> -->
                            </div>
                        </div>
                        <div class="row">
                            <!--/span-->
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee designation']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Designation')}}</label>
                                        <select class="form-control custom-select" name="designation" @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee designation']) &&
                        $perms['employee'][$employee->id]['employee designation']=="view") disabled="true" @endif>
                                            @foreach($designations as $designation)
                                                <option value="{{$designation->id}}"
                                                        @if($employee->designation ==$designation->designation_name) selected @endif>
                                                    {{$designation->designation_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- /span -->
                            @endif

                            @if(Auth::user()->isAdmin() || isset($perms['department'][$employee->id]['department name']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Department')}}</label>
                                        <select class="form-control custom-select"
                                                data-placeholder="Choose a Department" name="department_id" @if(Auth::user()->isNotAdmin() &&
                            isset($perms['department'][$employee->id]['department name']) && $perms['department'][$employee->id]['department name']=="view") disabled="true" @endif>
                                            @if($departments->count()>0)
                                                @foreach($departments as $department)
                                                    <option value="{{$department->id}}"
                                                            @if($department->id == $employee->department_id) selected @endif>
                                                        {{$department->department_name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee employment_status']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Employment Status')}}</label>
                                        <select class="form-control custom-select" name="employment_status_id" @if(Auth::user()->isNotAdmin()
                            && isset($perms['employee'][$employee->id]['employee employment_status']) && $perms['employee'][$employee->id]['employee employment_status']=="view")
                                        disabled="true" @endif>
                                            @foreach($employment_statuses as $employment_status)
                                                <option value="{{$employment_status->id}}" @if($employee->employmentStatus != '') @if($employment_status->id == $employee->employmentStatus->id)selected @endif @endif>
                                                    {{$employment_status->employment_status}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        <!--/span-->
                            @if(Auth::user()->isAdmin() || isset($perms['location'][$employee->id]['location name']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Location')}}</label>
                                        <select class="form-control custom-select" data-placeholder="Choose a Category"
                                                name="location_id" @if(Auth::user()->isNotAdmin() &&
                            isset($perms['location'][$employee->id]['location name']) && $perms['location'][$employee->id]['location name']=="view") disabled="true" @endif>
                                            @foreach($locations as $location)
                                                <option value="{{$location->id}}"
                                                        @if($location->id == $employee->location_id) selected @endif>
                                                    {{$location->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employees work_schedule']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Work Schedule')}}</label>
                                        <select class="form-control custom-select"
                                                data-placeholder="Choose a Work Schedule"
                                                name="work-schedule-id">
                                            <option value="">
                                                Select Work Schedule
                                            </option>
                                            @foreach($workSchedules as $workSchedule)
                                                <option value="{{$workSchedule->id}}"
                                                        @if($workSchedule->id == $employee->work_schedule_id) selected @endif>
                                                    {{$workSchedule->title}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Work Schedule')}}</label>
                                        <select class="form-control custom-select" disabled
                                                data-placeholder="Choose a Work Schedule"
                                                name="work-schedule-id">
                                            <option value="">
                                                Select Work Schedule
                                            </option>
                                            @foreach($workSchedules as $workSchedule)
                                                <option value="{{$workSchedule->id}}"
                                                        @if($workSchedule->id == $employee->work_schedule_id) selected @endif>
                                                    {{$workSchedule->title}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">{{__('language.Status')}}</label>
                                    <select class="form-control custom-select" name="status">
                                        <option value="0" @if($employee->status == "0") selected @endif>
                                            InActive
                                        </option>
                                        <option value="1" @if($employee->status == "1") selected @endif>Active
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee joining_date']))
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label text-right"
                                               for="joining_date">{{__('language.Hiring Date')}}</label>

                                        <input type="date" name="joining_date" id="joining_date" class="form-control"
                                               placeholder="{{__('language.Enter')}} {{__('language.Hiring Date')}}"
                                               value="{{old('joining_date',$employee->joining_date)}}" @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee joining_date']) &&
                        $perms['employee'][$employee->id]['employee joining_date']=="view") disabled @endif>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right"
                                           for="exit_date">{{__('language.Exit')}} {{__('language.Date')}}</label>
                                    <input type="date" id="exit_date" class="form-control"
                                           placeholder="Enter {{__('language.Exit')}} {{__('language.Date')}}"
                                           name="exit_date"
                                           value="{{old('exit_date',$employee->exit_date)}}">
                                </div>
                            </div>
                            @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob report_to']))
                                <div class="col-md-4">
                                    <label class="control-label text-right">{{__('language.Reports To')}}</label>
                                    <select class="form-control select2" name="manager_id">
                                        <option value="">Select</option>
                                        @foreach($employees as $manager)
                                            @if($manager->id != $employee->id)
                                                <option value="{{$manager->id}}" @if($manager->id == $employee->manager_id) selected @endif>{{$manager->firstname}} {{$manager->lastname}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || (Auth::user()->id != $employee->id && isset($perms['attendance']['all']) && array_intersect(['edit employee_attendance'], $perms['attendance']['all']) ))
                            <div class="col-md-4">
                                <label class="control-label text-right">{{__('language.Can')}} {{__('language.Mark')}} {{__('language.Own')}} {{__('language.Attendance')}}</label>
                                <select class="form-control select2" name="can_mark_attendance">
                                    <option value="">Select Permission</option>
                                    <option @if($employee->can_mark_attendance == 1) selected @endif value="1">Grant</option>
                                    <option @if($employee->can_mark_attendance == 0 || $employee->can_mark_attendance == null) selected @endif value="0">Revoke</option>
                                </select>
                            </div>
                            @endif

                        </div>
                        <hr>
                        <button class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('language.Save')}}
                        </button>
                    </form>
                </div>
               
                @if(isset($perms['employeeAccessLevel']['all']) && array_intersect(['manage setting employee_accesslevel'], $perms['employeeAccessLevel']['all'] ))
                    <div class="tab-pane fade @if(isset($role)) show active @endif" id="custom-change-role"
                         role="tabpanel" aria-labelledby="custom-content-settings-tab">
                        <h5>{{__('language.Roles')}} {{__('language.Assignment')}}</h5>
                        <hr>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-right">{{__('language.Roles')}}</label>
                                <select class="form-control custom-select" name="role_id" id="role">
                                    @if(isset($roles['employeeRoles']))
                                        <optgroup label="Employee Levels">
                                            @foreach($roles['employeeRoles'] as $role)
                                                <option value="{{$role->id}}"
                                                        @if(isset($roles['currentRole']) && $role->id == $roles['currentRole']->id)
                                                        selected @endif>{{$role->name}}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <option disabled="disabled"></option>
                                    @endif
                                    <option value="no-access" @if(isset($roles['noAccess'])) selected @endif>No Access
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 demo-checkbox" id="permissions">
                        </div>
                    </div>
                @endif
                <div class="tab-pane fade @if(isset($contact)) show active @endif" id="custom-content-profile"
                     role="tabpanel" aria-labelledby="custom-content-profile-tab">
                    <form class="form" id="edit-employee-contact"
                          action="@if(isset($locale)){{url($locale.'/employee-contact-info',$employee->id)}} @else {{url('en/employee-contact-info',$employee->id)}} @endif"
                          method="post"
                          enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PUT">
                        {{csrf_field()}}
                        <input name="work-schedule-id" type="hidden" value="{{$employee->work_schedule_id}}">
                        <div class="border-bottom mb-1">
                            <div class="head-label">
                                <h4><i class="font-medium-3 mr-25" data-feather='info'></i>{{__('language.Contact')}}
                                </h4>
                            </div>
                        </div>
                        @if(Auth::user()->isAdmin()
                        || isset($perms['employee'][$employee->id]['employee contact_no'])
                        || isset($perms['employee'][$employee->id]['employee nin']))
                        <!-- <h4><i class="mr-0 mr-sm-50" data-feather='alert-circle'></i>{{__('language.Contact')}} {{__('language.Information')}}</h4> -->
                            <!-- <hr class="m-t-0 m-b-40"> -->
                        @endif
                        <div class="row">
                            @if(Auth::user()->hasRole('admin') || isset($perms['employee'][$employee->id]['employee contact_no']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Phone')}} #</label>
                                        <input type="text" name="contact_no" class="form-control"
                                               placeholder="{{__('language.Enter')}} {{__('language.Contact')}}#"
                                               value="{{old('contact_no',$employee->contact_no)}}"
                                               @if(in_array('contact_no',$disableFields)) disabled @endif @if(Auth::user()->isNotAdmin() &&
                    isset($perms['employee'][$employee->id]['employee contact_no']) && $perms['employee'][$employee->id]['employee contact_no']=="view") disabled @endif
                                        >
                                    </div>
                                </div>
                            @endif
                        <!--/span-->
                            @if(Auth::user()->hasRole('admin') || isset($perms['employee'][$employee->id]['employee nin']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right ">NIN</label>
                                        <input type="text" name="nin" class="form-control " placeholder="Enter NIN"
                                               value="{{old('nin',$employee->nin)}}"
                                               @if(in_array('nin',$disableFields)) disabled @endif
                                               @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee nin']) && $perms['employee'][$employee->id]['employee nin']=="view") disabled
                                                @endif
                                        >
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!--/row-->
                        @if(Auth::user()->isAdmin()
                        || isset($perms['employee'][$employee->id]['employee emergency_contact'])
                        || isset($perms['employee'][$employee->id]['employee emergency_contact_relationship']))
                            <div class="border-bottom mb-1 mt-2">
                                <div class="head-label">
                                    <h4><i class="font-medium-3 mr-25"
                                           data-feather='alert-triangle'></i>{{__('language.Emergency Contact')}}</h4>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            @if(Auth::user()->hasRole('admin') || isset($perms['employee'][$employee->id]['employee emergency_contact']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Contact')}}#</label>
                                        <input type="text" class="form-control " placeholder="Enter Emergency Contact#"
                                               name="emergency_contact"
                                               @if(in_array('emergency_contact',$disableFields)) disabled
                                               @endif value="{{old('emergency_contact',$employee->emergency_contact)}}" @if(Auth::user()->isNotAdmin() &&
                    isset($perms['employee'][$employee->id]['employee emergency_contact']) && $perms['employee'][$employee->id]['employee emergency_contact']=="view") disabled @endif>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->hasRole('admin') || isset($perms['employee'][$employee->id]['employee emergency_contact_relationship']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Contact Relationship')}}</label>
                                        <select class="form-control custom-select" name="emergency_contact_relationship"
                                                @if(in_array('emergency_contact_relationship',$disableFields)) disabled
                                                @endif
                                                @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee emergency_contact_relationship'])
                                                && $perms['employee'][$employee->id]['employee emergency_contact_relationship']=="view") disabled="true" @endif>
                                            <option value="father"
                                                    @if($employee->emergency_contact_relationship == "father") selected @endif>
                                                Father
                                            </option>
                                            <option value="brother"
                                                    @if($employee->emergency_contact_relationship == "brother") selected @endif>
                                                Brother
                                            </option>
                                            <option value="mother"
                                                    @if($employee->emergency_contact_relationship == "mother") selected @endif>
                                                Mother
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!--/row-->
                        @if(Auth::user()->isAdmin()
                        || isset($perms['employee'][$employee->id]['employee current_address'])
                        || isset($perms['employee'][$employee->id]['employee parmanent_address'])
                        || isset($perms['employee'][$employee->id]['employee city']))
                            <div class="border-bottom mb-1 mt-2">
                                <div class="head-label">
                                    <h4><i class="font-medium-3 mr-25"
                                           data-feather='map-pin'></i>{{__('language.Address Details')}}</h4>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee current_address']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Current Address')}}</label>
                                        <textarea rows="2" class="form-control "
                                                  placeholder="{{__('language.Enter')}} {{__('language.Current Address')}}"
                                                  name="current_address"
                                                  @if(in_array('current_address',$disableFields)) disabled
                                                  @endif value="{{old('current_address',$employee->current_address)}}"
                                                  @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee current_address']) && $perms['employee'][$employee->id]['employee current_address']=="view") disabled @endif>{{$employee->current_address}}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee permanent_address']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.Permanent Address')}}</label>
                                        <textarea rows="2" class="form-control "
                                                  placeholder="{{__('language.Enter')}} {{__('language.Permanent Address')}}"
                                                  name="permanent_address"
                                                  @if(in_array('permanent_address',$disableFields)) disabled
                                                  @endif value="{{old('permanent_address',$employee->permanent_address)}}"
                                                  @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee permanent_address']) && $perms['employee'][$employee->id]['employee permanent_address']=="view") disabled @endif>{{$employee->permanent_address}}</textarea>
                                    </div>
                                </div>
                        @endif
                        <!--/span-->
                        </div>
                        <div class="row">
                            @if(Auth::user()->isAdmin() || isset($perms['employee'][$employee->id]['employee city']))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label text-right">{{__('language.City')}}</label>
                                        <input type="text" class="form-control " name="city"
                                               placeholder="{{__('language.Enter')}} {{__('language.City')}}"
                                               value="{{old('city',$employee->city)}}"
                                               @if(in_array('city',$disableFields)) disabled @endif @if(Auth::user()->isNotAdmin() && isset($perms['employee'][$employee->id]['employee city']) &&
                    $perms['employee'][$employee->id]['employee city']=="view") disabled @endif>
                                    </div>
                                </div>
                        @endif
                        <!--/span-->
                        </div>
                        <hr>
                        <button class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('language.Save')}}
                        </button>
                    </form>
                </div>
            <!-- <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <button class="btn btn-success" id="submit_update" type="submit"> {{__('language.Update')}}
                    </button>
            </div>
            </div>
            </div> -->

                <div class="tab-pane fade @if(isset($job)) show active @endif" id="custom-content-job" role="tabpanel" aria-labelledby="custom-content-job-tab">
                    {{-- TODO: Employee Job Information --}}
                    @include('admin.employees.employee_job_information')
                    {{-- TODO: End Employee Job Information --}}

                    {{-- TODO: Employee Employment status --}}
                    @include('admin.employees.employee_employment_status')
                    {{-- TODO: End Employee Employment status --}}

                    {{-- TODO: Employee Compensation --}}
                    @include('admin.employees.employee_compensation')
                    {{-- TODO: End Employee Compensation --}}
                </div>
                <div class="tab-pane fade @if(isset($education)) show active @endif" id="custom-education"
                     role="tabpanel" aria-labelledby="custom-education">
                    <div class="card-header border-bottom">
                        <div class="head-label">
                            <h4 class="mb-0"><i class="font-medium-3 mr-25"
                                                data-feather='book-open'></i>{{__('language.Education')}}</h4>
                            </h6>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons flex-wrap d-inline-flex">
                                @if(Auth::user()->isAdmin()
                                || (isset($perms['education'][$employee->id]) && (in_array('edit', $perms['education'][$employee->id]) || in_array('edit_with_approval', $perms['education'][$employee->id])))
                                || (isset($perms['educationType'][$employee->id]) && (in_array('edit', $perms['educationType'][$employee->id]) || in_array('edit_with_approval',
                                $perms['educationType'][$employee->id]))))
                                    <button type="button"
                                            class="btn btn-primary btn-rounded m-t-10 float-right employee-edit-btns"
                                            data-toggle="modal" data-target="#create"><i
                                                data-feather='plus'></i>{{__('language.Add')}}
                                        {{__('language.Education')}}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__body">
                            <table class="dt-employee-edit table dt-responsive display">

                                <thead>
                                <tr>
                                    @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]) || isset($perms['educationType'][$employee->id]))
                                        <th>#</th>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education institute_name']))
                                        <th> {{__('language.Institute Name')}}</th>@endif
                                    @if(Auth::user()->isAdmin() || isset($perms['educationType'][$employee->id]['educationtype education_type']))
                                        <th> {{__('language.Education Type')}}</th>@endif
                                    @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education major']))
                                        <th> {{__('language.Major')}}</th> @endif
                                    @if(Auth::user()->isAdmin()
                                    || (isset($perms['education'][$employee->id]) && (in_array('edit', $perms['education'][$employee->id]) || in_array('edit_with_approval',
                                    $perms['education'][$employee->id])))
                                    || (isset($perms['educationType'][$employee->id]) && (in_array('edit', $perms['educationType'][$employee->id]) || in_array('edit_with_approval',
                                    $perms['educationType'][$employee->id]))))
                                        <th> {{__('language.Actions')}}</th>
                                    @endif
                                </tr>
                                </thead>
                                @if($educations->count() > 0)
                                    <tbody>
                                    @foreach($educations as $key => $education)
                                        <tr>
                                            @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]) || isset($perms['educationType'][$employee->id]))
                                                <td>{{$key+1}}</td>
                                            @endif
                                            @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education institute_name']))
                                                <td>@if(isset($education->institute_name)){{$education->institute_name}} @endif </td>@endif
                                            @if(Auth::user()->isAdmin() || isset($perms['educationType'][$employee->id]['educationtype education_type']))
                                                <td>@if(isset($education->EducationType->education_type)){{$education->EducationType->education_type}} @endif</td>@endif
                                            @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education major']))
                                                <td>@if(isset($education->major)){{$education->major}} @endif</td>@endif
                                            @if(Auth::user()->isAdmin()
                                            || (isset($perms['education'][$employee->id]) && (in_array('edit', $perms['education'][$employee->id]) || in_array('edit_with_approval',
                                            $perms['education'][$employee->id])))
                                            || (isset($perms['educationType'][$employee->id]) && (in_array('edit', $perms['educationType'][$employee->id]) || in_array('edit_with_approval',
                                            $perms['educationType'][$employee->id]))))
                                                <td class="text-nowrap">
                                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                       data-toggle="modal" data-target="#edit-edu-{{$education->id}}"
                                                       data-original-title="Edit">
                                                        <i data-feather='edit-2'></i></a>
                                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                       data-toggle="modal"
                                                       data-target="#confirm-delete-edu-{{ $education->id }}"
                                                       data-original-title="Close">
                                                        <i data-feather='trash-2'></i></a>
                                                    <div class="modal fade" id="confirm-delete-edu-{{ $education->id }}" role="dialog" aria-labelledby="myModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="@if(isset($locale)){{url($locale.'/education/destroy',$education->id)}} @else {{url('en/education/destroy',$education->id)}} @endif"
                                                                      method="post">
                                                                    {{ csrf_field() }}
                                                                    <div class="modal-header">
                                                                        {{__('language.Are you sure you want to delete this Employee Education?')}}
                                                                    </div>
                                                                    <div class="modal-header">
                                                                        <h4>{{ $education->institute_name }}</h4>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default"
                                                                                data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                        <button type="submit"
                                                                                class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>
                                        <div class="modal modal-slide-in fade" id="edit-edu-{{$education->id}}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content pt-0">
                                                    <form action="@if(isset($locale)){{url($locale.'/education/update',$education->id)}} @else {{url('en/education/update',$education->id)}} @endif"
                                                          method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            {{__('language.Update')}} {{__('language.Education')}}
                                                        </div>
                                                        <input type="hidden" value="{{$employee->id}}"
                                                               name="employee_id">
                                                        <div class="card-body">
                                                            <div class="col-md-12">
                                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education institute_name']) &&
                                                                $perms['education'][$employee->id]['education institute_name'] != "view")
                                                                    <div class="form-group">
                                                                        <label class="control-label">{{__('language.Institue Name')}}</label>
                                                                        <input type="text" name="institute_name"
                                                                               value="{{old('employmentStatuses',$education->institute_name)}}"
                                                                               @if(in_array('institute_name',$disableFields)) disabled
                                                                               @endif
                                                                               placeholder="{{__('language.Enter')}} {{__('language.Institue Name')}} {{__('language.here')}}"
                                                                               class="form-control">
                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->hasRole('admin') || isset($perms['educationType'][$employee->id]['educationtype education_type']) &&
                                                                $perms['educationType'][$employee->id]['educationtype education_type'] != "view")
                                                                    <div class="form-group">
                                                                        <label class="control-label">{{__('language.Education Type')}}</label>
                                                                        <select name="education_type_id"
                                                                                @if(in_array('education_type_id',$disableFields)) disabled
                                                                                @endif class="form-control">
                                                                            <option value="">Select</option>
                                                                            @foreach($educationtypes as $key => $educationtype)
                                                                                <option value="{{$educationtype->id}} "
                                                                                        @if($educationtype->id == $education->education_type_id) selected @endif>
                                                                                    {{$educationtype->education_type}}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education major']) &&
                                                                $perms['education'][$employee->id]['education major'] != "view")
                                                                    <div class="form-group">
                                                                        <label class="control-label">{{__('language.Major')}}</label>
                                                                        <input type="text" name="major"
                                                                               placeholder="{{__('language.Enter')}} {{__('language.Major')}} {{__('language.here')}}"
                                                                               class="form-control"
                                                                               @if(in_array('major',$disableFields)) disabled
                                                                               @endif
                                                                               value="{{old('employmentStatuses',$education->major)}}">
                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education cgpa'])
                                                                && $perms['education'][$employee->id]['education cgpa']!= "view")
                                                                    <div class="form-group">
                                                                        <label class="control-label">{{__('language.CGPA')}}</label>
                                                                        <input type="text" name="cgpa"
                                                                               @if(in_array('cgpa',$disableFields)) disabled
                                                                               @endif
                                                                               placeholder="{{__('language.Enter')}} {{__('language.CGPA')}} {{__('language.here')}}"
                                                                               class="form-control"
                                                                               value="{{old('employmentStatuses',$education->cgpa)}}">
                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education date_start']) &&
                                                                $perms['education'][$employee->id]['education date_start'] != "view")
                                                                    <div class="form-group">
                                                                        <label class="control-label"
                                                                               for="date_start">{{__('language.Start Date')}}</label>
                                                                        <input id="date-start" name="date_start"
                                                                               class="form-control flatpickr-input"
                                                                               type="date"
                                                                               @if(in_array('date_start',$disableFields)) disabled
                                                                               @endif value="{{old('employmentStatuses',$education->date_start)}}">

                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education date_end'])
                                                                && $perms['education'][$employee->id]['education date_end'] != "view")
                                                                    <div class="form-group">
                                                                        <label class="control-label"
                                                                               for="date_end">{{__('language.End Date')}}</label>
                                                                        <input id="date-end" name="date_end"
                                                                               class="form-control flatpickr-input"
                                                                               type="date"
                                                                               @if(in_array('date_end',$disableFields)) disabled
                                                                               @endif
                                                                               value="{{old('employmentStatuses',$education->date_end)}}">

                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn waves-effect"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-primary data-submit mr-1 waves-effect waves-float waves-light">{{__('language.Update')}} {{__('language.Education Type')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    @endif
                                    </tbody>

                            </table>


                            <!--end: Datatable -->
                            <div class="modal modal-slide-in fade" id="create" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content pt-0">
                                        <form id="create_education_form"
                                              action="@if(isset($locale)){{url($locale.'/education/store')}}@else{{url('en/education/store')}}@endif"
                                              method="post">
                                            {{ csrf_field() }}
                                            <div class="modal-header">
                                                {{__('language.Create')}} {{__('language.Education')}}
                                            </div>
                                            <div class="col-md-12">
                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education institute_name'])
                                                && $perms['education'][$employee->id]['education institute_name'] != "view")
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Institue Name')}}</label>
                                                        <input type="text" name="institute_name"
                                                               placeholder="{{__('language.Enter')}} {{__('language.Institue Name')}} {{__('language.here')}}"
                                                               class="form-control">
                                                    </div>
                                                @endif
                                                <input type="hidden" value="{{$employee->id}}" name="employee_id">
                                                @if(Auth::user()->hasRole('admin') || isset($perms['educationType'][$employee->id]['educationtype education_type']) &&
                                                $perms['educationType'][$employee->id]['educationtype education_type'] != "view")
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Education Type')}}</label>
                                                        <select name="education_type_id" class="form-control">
                                                            <option value="">Select</option>
                                                            @foreach($educationtypes as $key => $educationtype)
                                                                <option value="{{$key+1}}">{{$educationtype->education_type}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education major'])
                                                && $perms['education'][$employee->id]['education major'] != "view")
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Major')}}</label>
                                                        <input type="text" name="major"
                                                               placeholder="{{__('language.Enter')}} {{__('language.Major')}} {{__('language.here')}}"
                                                               class="form-control">
                                                    </div>
                                                @endif
                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education cgpa'])
                                                && $perms['education'][$employee->id]['education cgpa'] != "view")
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.CGPA')}}</label>
                                                        <input type="text" name="cgpa"
                                                               placeholder="{{__('language.Enter')}} {{__('language.CGPA')}} {{__('language.here')}}"
                                                               class="form-control">
                                                    </div>
                                                @endif

                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education date_start'])
                                                && $perms['education'][$employee->id]['education date_start'] != "view")
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Start Date')}}</label>
                                                        <h3 class="">
                                                <span class=" =">
                                                    <input id="date_start" value="" class="form-control"
                                                           type="date" name="date_start">
                                                </span>
                                                        </h3>
                                                    </div>
                                                @endif
                                                @if(Auth::user()->isAdmin() || isset($perms['education'][$employee->id]['education date_end'])
                                                && $perms['education'][$employee->id]['education date_end'] != "view")
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.End Date')}}</label>
                                                        <h3 class="">
                                                <span class=" ">
                                                    <input id="date_end" value="" class="form-control"
                                                           type="date" name="date_end">
                                                </span>
                                                        </h3>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                <button type="submit"
                                                        class="btn btn-primary btn-ok">{{__('language.Add')}} {{__('language.Education')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if(isset($visa)) show active @endif" id="custom-visa" role="tabpanel"
                     aria-labelledby="custom-visa">

                    <div class="card-header border-bottom">
                        <div class="head-label">
                            <h4 class="mb-0"><i class="font-medium-3 mr-25"
                                                data-feather='clipboard'></i>{{__('language.Visa')}}</h4>
                        </div>
                        <div class="dt-action-buttons text-right">
                            <div class="dt-buttons flex-wrap d-inline-flex">
                                @if(Auth::user()->isAdmin()
                                || (isset($perms['visaType'][$employee->id]['visatype visa_type']) && ($perms['visaType'][$employee->id]['visatype visa_type'] != "view"))
                                && (isset($perms['employeeVisa'][$employee->id]['employeevisa country_id']) && ( $perms['employeeVisa'][$employee->id]['employeevisa country_id'] != "view"))
                                )
                                    <button type="button"
                                            class="btn btn-primary btn-rounded float-right employee-edit-btns"
                                            data-toggle="modal" data-target="#create1"><i
                                                data-feather='plus'></i>{{__('language.Add')}}
                                        {{__('language.Visa')}}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <table class="dt-employee-edit table dt-responsive display">
                            <thead>
                            <tr>
                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]) || isset($perms['visaType'][$employee->id]))
                                    <th>#</th>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['visaType'][$employee->id]['visatype visa_type']))
                                    <th> {{__('language.Visa Type')}}
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa country_id']))
                                    <th> {{__('language.Country Name')}}</th>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa issue_date']))
                                    <th> {{__('language.issue_date')}}</th>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa expire_date']))
                                    <th> {{__('language.expire_date')}}</th>
                                @endif
                                @if(Auth::user()->isAdmin()
                                || (isset($perms['employeeVisa'][$employee->id]) && (in_array('edit', $perms['employeeVisa'][$employee->id]) || in_array('edit_with_approval',
                                $perms['employeeVisa'][$employee->id])))
                                || (isset($perms['visaType'][$employee->id]) && (in_array('edit', $perms['visaType'][$employee->id]) || in_array('edit_with_approval',
                                $perms['visaType'][$employee->id]))))
                                    <th> {{__('language.Actions')}}
                                @endif
                            </tr>
                            </thead>
                            @if($visas->count() > 0)
                                <tbody>
                                @foreach($visas as $key => $visa)
                                    <tr>
                                        @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]) || isset($perms['visaType'][$employee->id]))
                                            <td>{{$key+1}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($perms['visaType'][$employee->id]['visatype visa_type']))
                                            <td>{{$visa->VisaType->visa_type}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa country_id']))
                                            <td>{{$visa->Country->name}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa issue_date']))
                                            <td>{{$visa->issue_date}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa expire_date']))
                                            <td>{{$visa->expire_date}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin()
                                        || (isset($perms['employeeVisa'][$employee->id]) && (in_array('edit', $perms['employeeVisa'][$employee->id])
                                        || in_array('edit_with_approval', $perms['employeeVisa'][$employee->id])))
                                        || (isset($perms['visaType'][$employee->id]) && (in_array('edit', $perms['visaType'][$employee->id])
                                        || in_array('edit_with_approval',$perms['visaType'][$employee->id]))))
                                            <td class="text-nowrap">
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                                   data-target="#visaEdit{{$visa->id}}" data-original-title="Edit">
                                                    <i data-feather='edit-2'></i></a>
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                                   data-target="#visa-confirm-delete{{$visa->id}}"
                                                   data-original-title="Close">
                                                    <i data-feather='trash-2'></i> </a>
                                                <div class="modal fade" id="visa-confirm-delete{{ $visa->id }}" role="dialog" aria-labelledby="myModalLabel"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="@if(isset($locale)){{url($locale.'/visa/destroy',$visa->id)}} @else {{url('en/visa/destroy',$visa->id)}} @endif"
                                                                  method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    {{__('language.Are you sure you want to delete this Employee Visa?')}}
                                                                </div>
                                                                <div class="modal-header">
                                                                    <h4>{{ $visa->VisaType->visa_type }}</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                                    <button type="submit"
                                                                            class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <div class="modal modal-slide-in fade" id="visaEdit{{$visa->id}}"
                                             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content pt-0">
                                                    <form action="@if(isset($locale)){{url($locale.'/visa/update',$visa->id)}} @else {{url('en//update',$visa->id)}} @endif"
                                                          method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            {{__('language.Update')}} {{__('language.Visa')}}
                                                        </div>
                                                        <input type="hidden" value="{{$employee->id}}"
                                                               name="employee_id">
                                                        <div class="col-md-12">
                                                            @if(Auth::user()->isAdmin() || isset($perms['visaType'][$employee->id]['visatype visa_type']))
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Visa Type')}}</label>
                                                                    <select name="visa_type_id" class="form-control" @if(Auth::user()->isNotAdmin()
                                                && isset($perms['visaType'][$employee->id]['visatype visa_type']) && $perms['visaType'][$employee->id]['visatype visa_type'] == "view")
                                                                    disabled="true" @endif required>
                                                                        <option value="">Select</option>
                                                                        @if(isset($VisaTypes))
                                                                            @foreach($VisaTypes as $key => $VisaType)
                                                                                <option value="{{$VisaType->id}}"
                                                                                        @if($VisaType->id == $visa->visa_type_id) selected @endif>
                                                                                    {{$VisaType->visa_type}}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa country_id']))
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Country Name')}}</label>
                                                                    <select name="country_id" class="form-control" @if(Auth::user()->isNotAdmin()
                                                && isset($perms['employeeVisa'][$employee->id]['employeevisa country_id'])
                                                && $perms['employeeVisa'][$employee->id]['employeevisa country_id'] == "view") disabled="true"
                                                                            @endif required>
                                                                        <option value="">Select</option>
                                                                        @foreach($countries as $key => $country)
                                                                            <option value="{{$country->id}}"
                                                                                    @if($country->id == $visa->country_id) selected @endif >
                                                                                {{$country->name}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa note']))
                                                                <div class="form-group">
                                                                    <label class="control-label">{{__('language.Note')}}</label>
                                                                    <input type="text" name="note"
                                                                           placeholder="{{__('language.Enter')}} {{__('language.Note')}} {{__('language.here')}}"
                                                                           class="form-control"
                                                                           value="{{old('employmentStatuses',$visa->note)}}" @if(Auth::user()->isNotAdmin() &&
                                            isset($perms['employeeVisa'][$employee->id]['employeevisa note'])
                                            && $perms['employeeVisa'][$employee->id]['employeevisa note'] == "view") disabled @endif>
                                                                </div>
                                                            @endif
                                                            <div class="row">
                                                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa issue_date']))
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">{{__('language.Start Date')}}</label>
                                                                            <h3 class="">
                                                        <span class=" =">
                                                            <input id="issue_date" class="form-control"
                                                                   type="date" name="issue_date"
                                                                   value="{{$visa->issue_date}}"
                                                                   @if(Auth::user()->isNotAdmin() && isset($perms['employeeVisa'][$employee->id]['employeevisa issue_date'])
                                                               && $perms['employeeVisa'][$employee->id]['employeevisa issue_date'] == "view") disabled @endif>
                                                        </span>
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa expire_date']))
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">{{__('language.End Date')}}</label>
                                                                            <h3 class="">
                                                        <span class=" ">
                                                            <input id="expire_date" class="form-control"
                                                                   type="date" name="expire_date"
                                                                   value="{{$visa->expire_date}}"
                                                                   @if(Auth::user()->isNotAdmin() && isset($perms['employeeVisa'][$employee->id]['employeevisa expire_date']) &&
                                                               $perms['employeeVisa'][$employee->id]['employeevisa expire_date'] == "view") disabled @endif>
                                                        </span>
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-success btn-ok">{{__('language.Update')}} {{__('language.Visa Type')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                        </table>
                        <!--end: Datatable -->
                        <div class="modal modal-slide-in fade" id="create1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content pt-0">
                                    <form id="create_visa"
                                          action="@if(isset($locale)){{url($locale.'/visa/store')}}@else{{url('en/visa/store')}}@endif"
                                          method="post">
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                            {{__('language.Create')}} {{__('language.Visa')}}
                                        </div>
                                        <div class="col-md-12">
                                            @if(Auth::user()->isAdmin() || isset($perms['visaType'][$employee->id]['visatype visa_type']))
                                                <div class="form-group">
                                                    <label class="control-label">{{__('language.Visa Name')}}</label>
                                                    <select name="visa_type_id" class="form-control" @if(Auth::user()->isNotAdmin() && isset($perms['visaType'][$employee->id]['visatype visa_type']) &&
                                    $perms['visaType'][$employee->id]['visatype visa_type'] == "view") disabled="true"
                                                            @endif required>
                                                        <option value="">Select</option>
                                                        @foreach($VisaTypes as $key => $VisaType)
                                                            <option value="{{$key+1}}">{{$VisaType->visa_type}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            <input type="hidden" value="{{$employee->id}}" name="employee_id">
                                            @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa country_id']))
                                                <div class="form-group">
                                                    <label class="control-label">{{__('language.Country Name')}}</label>
                                                    <select name="country_id" class="form-control" @if(Auth::user()->isNotAdmin() && isset($perms['employeeVisa'][$employee->id]['employeevisa country_id']) &&
                                    $perms['employeeVisa'][$employee->id]['employeevisa country_id'] == "view") disabled="true"
                                                            @endif required>
                                                        <option value="">Select</option>
                                                        @foreach($countries as $key => $country)
                                                            <option value="{{$key+1}}">{{$country->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa note']))
                                                <div class="form-group">
                                                    <label class="control-label">{{__('language.Note')}}</label>
                                                    <input type="text" name="note"
                                                           placeholder="{{__('language.Enter')}} {{__('language.Note')}} {{__('language.here')}}"
                                                           class="form-control"
                                                           @if(Auth::user()->isNotAdmin() && isset($perms['employeeVisa'][$employee->id]['employeevisa note']) &&
                                                       $perms['employeeVisa'][$employee->id]['employeevisa note'] == "view") disabled @endif>
                                                </div>
                                            @endif
                                            <div class="row">
                                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa issue_date']))
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">{{__('language.Start Date')}}</label>
                                                            <h3 class="">
                                            <span class=" =">
                                                <input id="issue_date" class="form-control" type="date"
                                                       name="issue_date" @if(Auth::user()->isNotAdmin() &&
                                                isset($perms['employeeVisa'][$employee->id]['employeevisa issue_date'])
                                                && $perms['employeeVisa'][$employee->id]['employeevisa issue_date'] == "view") disabled @endif>
                                            </span>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(Auth::user()->isAdmin() || isset($perms['employeeVisa'][$employee->id]['employeevisa expire_date']))
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">{{__('language.End Date')}}</label>
                                                            <h3 class="">
                                            <span class=" ">
                                                <input id="expire_date" class="form-control" type="date"
                                                       name="expire_date" @if(Auth::user()->isNotAdmin() &&
                                                isset($perms['employeeVisa'][$employee->id]['employeevisa expire_date'])
                                                && $perms['employeeVisa'][$employee->id]['employeevisa expire_date'] == "view") disabled @endif>
                                            </span>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                            <button type="submit"
                                                    class="btn btn-primary btn-ok">{{__('language.Add')}} {{__('language.Visa')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--Requested change Here -->
                @if($requestApprovals->isNotEmpty())
                    <div class="tab-pane fade @if(isset($approval)) show active @endif" id="request-change-tab"
                         role="tabpanel" aria-labelledby="custom-request-change">

                        <div class="kt-portlet kt-portlet--mobile">

                            <h5>{{__('language.Request a change')}} : {{$employee->firstname}}
                                &nbsp {{$employee->lastname}}</h5>
                            <hr>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Request a change
                                    <i class="caret"></i></button>
                                <ul class="dropdown-menu p-3">
                                    @foreach($requestApprovals as $approval)
                                        <li><a
                                                    href="@if(isset($locale)){{url($locale.'/employees', [$employee->id, 'request-change', $approval->id, 'edit'])}}@else{{url('en/employees', [$employee->id, 'request-change', $approval->id, 'edit'])}}@endif">{{$approval->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        @endif
        <!--Requested change Ends Here -->
        </div>
        <!--begin: Form Actions -->

        <!----Form End---->
    </div>
    {{---/Card Body ---}}
    </div>
    {{--Card End---}}
@endsection
@section('vendor-script')
    {{--   vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>


@endsection
@section('page-script')
    {{--   Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-employee-edit-validations.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-employee-salary.js')) }}"></script>
    <script>

        function createJob() {
            var employeeCreateJobForm = $('#create_job_form');
            if (employeeCreateJobForm.length) {
                employeeCreateJobForm.validate({
                    rules: {
                        'effective_date': {
                            required: true
                        },
                    },
                    messages: {
                        'effective_date': 'Effective date is required',
                    }
                });
            }
        }
    </script>
    <script>

        $(document).ready(function () {
// Picker
            if ($('#date-start').length) {
                $('#date-start').flatpickr({
                    onReady: function (selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    }
                });
            }
            $("#picture").change(function () {
                readURL(this);
            });// Picker
            if ($('#date-end').length) {
                $('#date-end').flatpickr({
                    onReady: function (selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    }
                });
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#custom-content-tab a[href="#{{ old('tab') }}"]').tab('show')
        });
    </script>
    <script>

        // $("[name='contact_no']").focusout(function(){

        //     var value = $(this).val();
        //     var result = value.match(/(\+\d{0,2}-)?[\s.-]?\(?\d{3}\)?[\s.-]?\d{3}?\d{4,5}$/);
        //     if(value === result){
        //        console.log("valid input")
        //        $(this).after('<span class="error">invalid input</span>');
        //     }
        //     else{
        //         console.log("invalid input")
        //        $(this).after('<span class="error">invalid input</span>');
        //     }
        //     //console.log(value.match == "/(\+\d{0,2}-)?[\s.-]?\(?\d{3}\)?[\s.-]?\d{3}?\d{4,5}$/");
        // });
        $('.dt-job-information').DataTable();
        $('.dt-employee-edit').DataTable();
    </script>
    <script>
        $('#save').click(function () {
// $('#confirm').modal('show');
            $("#edit-employee-job").submit();
        })
    </script>

@endsection
{{--@stop--}}
