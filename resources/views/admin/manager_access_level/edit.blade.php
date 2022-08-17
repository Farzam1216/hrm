@extends('layouts/contentLayoutMaster')
@section('title','Edit Manager Access Level')
@section('heading')

@stop
@section('content')
<div class="row">
    <div class="container-fluid">
        <form id="managerRole" action="@if(isset($locale)){{url($locale.'/access-level/manager/'.$role->id)}}@else{{url('en/access-level/manager/'.$role->id)}}@endif" method="post" enctype="multipart/form-data">
            @method('PUT')
            {{csrf_field()}}
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1 pl-1 pr-1">
                    <ul class="nav nav-tabs nav-justified" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active step" id="basic-info-tab" aria-controls="basic-info" aria-selected="true">{{__('language.Basic')}} {{__('language.Info')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link step" id="what-sees-tab" aria-controls="what-sees" aria-selected="false">{{__('language.What this access level can see')}}?</a>
                        </li>
                    </ul>
                </div>
                <div class="">
                    <div class="tab-content " id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade tab show active" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                            <div class="form-group col-md-12">
                                <br />
                                <p>
                                    Manager Access Levels can be configured to allow Managers to see specific information about their direct and/or indirect reports only.
                                </p>
                                <hr>
                                <div class="form-group ">
                                    <label class="control-label">Access Level Name
                                        <span class="text-danger">*</span></label>
                                    <input class="form-control col-md-6" maxlength="100" type="text" name="name" value="{{$role->name}}" id="access_level_name" />
                                        <div class="invalid-feedback">Please enter access level name.</div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label">Description</label>
                                    <textarea class="form-control" name="description">{{$role->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane  tab" id="what-sees" role="tabpanel" aria-labelledby="what-sees-tab">
                            <div class="col-12">
                                <br />
                                <p><b>What People with this access level can see?</b></p>
                                <hr>
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs nav-justified" id="custom-tabs-one-tab" role="tablist">
                                            <li class="nav-item">

                                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">
                                                    <div class="row text-left">
                                                        <div class="col-md-1">
                                                            <i data-feather='users' style="height: 35px; width: 35px;"></i>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 7px; margin-left: 20px;">
                                                                See About Other Employees<br />
                                                            </div>
                                                            <div style="margin-top: 7px; margin-left: 20px;">
                                                                <small>Choose what people with this access level
                                                                can see about other
                                                                employees.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">
                                                    <div class="row text-left">
                                                        <div class="col-md-1">
                                                            <i data-feather='user' style="height: 35px; width: 35px;"></i>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 7px; margin-left: 20px;">
                                                                See About Themselves
                                                            </div>
                                                            <div style="margin-top: 7px; margin-left: 20px;">
                                                                <small>Choose what people
                                                                with this access level will see about
                                                                themselves.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">
                                                    <div class="row text-left">
                                                        <div class="col-md-1">
                                                            <i data-feather='home' style="height: 35px; width: 35px;"></i>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 7px; margin-left: 20px;">
                                                                See on Home
                                                            </div>
                                                            <div style="margin-top: 7px; margin-left: 20px;">
                                                                Choose what should show on Home
                                                                for people with this Access Level.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li> -->
                                        </ul>
                                    </div>
                                    <div class="">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                <div class="form-group row">
                                                    <label class="col-form-label pl-1">
                                                        <i data-feather='lock'></i>&nbsp;
                                                        <b>This Access Level can access the information below for</b>
                                                    </label>
                                                    <div class="pl-1">
                                                        <select class="form-control mr-1" name="managerRole[access-level]">
                                                            <option value="1" @if($accessLevel == 1) selected @endif>Direct Reports</option>
                                                            <option value="2" @if($accessLevel == 2) selected @endif> Direct + Indirect Reports </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="">
                                                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                        @if(isset($defaultPermissions))
                                                        @php $count=0; @endphp
                                                        @foreach($defaultPermissions as $category=>$permissionGroup)
                                                        <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                                                            <a class="nav-link @if($count==0) active @endif" id="employee-{{$category}}-info-tab" data-toggle="pill" href="#employee-{{$category}}-info" role="tab" aria-controls="employee-{{$category}}-info" aria-selected="true">{{ucwords (str_replace ('_', ' ', $category))}}</a>
                                                        </li>
                                                        @php $count++; @endphp
                                                        @endforeach
                                                        @endif
                                                    </ul>
                                                    <div class="tab-content" id="custom-content-below-tabContent">
                                                        @if(isset($defaultPermissions))
                                                        @php $count=0; @endphp
                                                        @foreach($defaultPermissions as $category=>$permissionGroup)
                                                        <div class="tab-pane fade show @if($count==0) active @endif" id="employee-{{$category}}-info" role="tabpanel" aria-labelledby="employee-{{$category}}-info-tab">
                                                            <div class="row">
                                                                <h3 class="col-md-7 pt-2">
                                                                    {{ucwords (str_replace ('_', ' ', $category))}}
                                                                </h3>
                                                                <div class="col-md-5">
                                                                    <div class="dropdown flex-fill align-self-end text-right">
                                                                        <a class="btn btn-primary dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Set Access on all fields
                                                                        </a>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                            <a class="dropdown-item set_all" category="{{$category}}" value="no_access">No Access</a>
                                                                            <a class="dropdown-item set_all" category="{{$category}}" value="view">View Only</a>
                                                                        </div>
                                                                    </div>
                                                                    <h6 class="align-self-end text-right" style="margin-top: 7px;">
                                                                        Set all fields to No Access to hide the Personal tab.
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            @php
                                                                $hasTimeOffTypes=false;
                                                            @endphp
                                                            @foreach($defaultPermissions[$category] as $group=>$permission)
                                                            @if($group == "no_group")
                                                            @foreach($permission as $fieldName=>$permissionActions)
                                                                        @php
                                                                            if($category == "time_off")
                                                                                $hasTimeOffTypes=true;
                                                                        @endphp
                                                            <div class="row col-md-12 pl-2">
                                                                <div class="mt-2 flex-fill flex-grow-1 col-md-3">
                                                                    {{ucwords(str_replace ('_', ' ', $fieldName))}}
                                                                </div>
                                                                <div class="row col-lg-9 col-md-12 col-sm-12">
                                                                    <div class="mt-2 flex-fill col-md-7 col-sm-4">
                                                                        @php
                                                                        $id = $group.$fieldName;
                                                                        $consumedId =false;
                                                                        @endphp
                                                                        @foreach($permissionActions as $key => $action)
                                                                        @if ($id !== $consumedId)
                                                                        @if (strpos($action,"view")!== false && $key === 'checked')
                                                                        <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                        <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view"> <i data-feather='eye'></i> View Only</p>
                                                                        <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit" style="display: none"> <i data-feather='edit-2'></i> Edit</p>
                                                                        <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval" style="display: none"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                        <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access" style="display: none"> <i data-feather='lock'></i> No Access</p>
                                                                        @php $consumedId = $id; @endphp
                                                                        @elseif(strpos($action,"edit")!== false && $key === 'checked')
                                                                        <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                        <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view" style="display: none"> <i data-feather='eye'></i> View Only</p>
                                                                        <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit"> <i data-feather='edit-2'></i> Edit</p>
                                                                        <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval" style="display: none"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                        <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access" style="display: none"> <i data-feather='lock'></i> No Access</p>
                                                                        @php $consumedId = $id; @endphp
                                                                        @elseif(strpos($action,"edit_with_approval")!== false && $key === 'checked')
                                                                        <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                        <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view" style="display: none"> <i data-feather='eye'></i> View Only</p>
                                                                        <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit" style="display: none"> <i data-feather='edit-2'></i> Edit</p>
                                                                        <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                        <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access" style="display: none"> <i data-feather='lock'></i> No Access</p>
                                                                        @php $consumedId = $id; @endphp
                                                                        @elseif($key !== 'checked')
                                                                        <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                        <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view" style="display: none"> <i data-feather='eye'></i> View Only</p>
                                                                        <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit" style="display: none"> <i data-feather='edit-2'></i> Edit</p>
                                                                        <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval" style="display: none"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                        <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access"> <i data-feather='lock'></i> No Access</p>
                                                                        @php $consumedId = $id; @endphp
                                                                        @endif
                                                                        @endif
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="mt-1 flex-fill col-md-5 col-sm-6">
                                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                            @php $flag = true; @endphp
                                                                            @foreach($permissionActions as $key => $action)
                                                                            @if($flag )
                                                                            <label class="btn btn-primary p-1 {{$category}}_no_access_button @if($key !== 'checked') active @endif"><i data-feather='lock'></i>
                                                                                <input type="radio" class="{{$category}}_no_access" access="no_access" id="{{$group}}{{$fieldName}}no_access" value="{{$group}}{{$fieldName}}" permission="" onclick="getPermissionValue(this);" @if($key !=='checked' )checked @endif>
                                                                            </label>
                                                                            @php $flag = false; @endphp
                                                                            @endif
                                                                            @if(strpos($action, 'view') !== false)
                                                                            <label class="btn btn-primary p-1 {{$category}}_view_button @if($key === 'checked') active @endif "><i data-feather='eye'></i>
                                                                                <input type="radio" class="{{$category}}_view" access="view" id="{{$group}}{{$fieldName}}view" permission="{{$action}}" value="{{$group}}{{$fieldName}}" onclick="getPermissionValue(this);" @if(strpos($action,"view")!==false && $key==='checked' ) checked @endif>
                                                                            </label>
                                                                            @elseif(strpos($action, 'edit ') !== false)
                                                                            <label class="btn btn-primary p-1 {{$category}}_edit_button @if($key === 'checked') active @endif"><i data-feather='edit-2'></i>
                                                                                <input type="radio" class="{{$category}}_edit" access="edit" id="{{$group}}{{$fieldName}}edit" permission="{{$action}}" value="{{$group}}{{$fieldName}}" onclick="getPermissionValue(this);" @if(strpos($action,"edit")!==false && $key==='checked' ) checked @endif>
                                                                            </label>
                                                                            @elseif(strpos($action, 'edit_with_approval') !== false)
                                                                            <label class="btn btn-primary p-1 {{$category}}_edit_with_approval_button @if($key === 'checked') active @endif"><i data-feather='user-check'></i>
                                                                                <input type="radio" class="{{$category}}_edit_with_approval" access="edit_with_approval" id="{{$group}}{{$fieldName}}edit_with_approval" permission="{{$action}}" value="{{$group}}{{$fieldName}}" onclick="getPermissionValue(this);" @if(strpos($action,"edit_with_approval")!==false && $key==='checked' ) checked @endif>
                                                                            </label>
                                                                            @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @elseif($group == "checkbox")
                                                                @if($category == "performance")
                                                                    <div class="custom-control custom-checkbox mt-1 ml-1">
                                                                        <input class="custom-control-input" type="checkbox" id="{{$category}}" value="@if(isset($permission[$category]['checked'])) {{$permission[$category]['checked']}} @else {{$permission[$category][0]}} @endif" name="employeePermission[]" @if(isset($permission[$category]['checked'])) checked @endif>
                                                                        <label for="{{$category}}" class="custom-control-label">Manage {{ucwords($category)}}</label>
                                                                    </div>
                                                                @endif

                                                                @if($category == "attendance")
                                                                    @foreach($permission as $permissionLabel=>$permissionValue)
                                                                        @if(isset($permission['can_view_employee_attendance']['can_mark_employee_attendance']['checked']))
                                                                            @php        
                                                                                $permissionName = $permission['can_view_employee_attendance']['can_mark_employee_attendance']['checked'];
                                                                            @endphp 
                                                                        @else
                                                                            @php
                                                                                $permissionName ="";
                                                                            @endphp      
                                                                        @endif
                                                                    @endforeach
                                                                    <div class="custom-control custom-checkbox mt-1 ml-1">
                                                                        <input class="custom-control-input requestAttendance" type="checkbox" id="{{$category}}" @if($permissionName == "view employee_attendance" || $permissionName == "edit employee_attendance")) checked @endif value="view employee_attendance" name="managerRole[permission][]">
                                                                        <label for="{{$category}}" class="custom-control-label">Can View Employee Attendance</label>
                                                                    </div>
                                                                    <div class="col-12 attendanceList" @if ($permissionName == "edit employee_attendance" || $permissionName == "view employee_attendance")  style="display:block; width:  270px; border: 1px solid grey; border-radius: 5px; margin-left: 30px; margin-top: 2px; padding: 2px;" @else style="display:none; width:  270px; border: 1px solid grey; border-radius: 5px; margin-left: 30px; margin-top: 2px; padding: 2px; border: 1px solid grey;" @endif>
                                                                        @foreach($permission as $fieldName=>$permissionActions)
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input class="custom-control-input myCheckbox" @if($permissionName == "edit employee_attendance")) checked @endif type="checkbox" id="{{$fieldName}}" value="edit employee_attendance" name="managerRole[permission][]">
                                                                            <label for="{{$fieldName}}" class="custom-control-label">Can Mark Employee Attendance</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                @if($category == "time_off" && isset($permission['time_off_decision']))
                                                                    <div class="custom-control custom-checkbox m-1">
                                                                        <input class="custom-control-input" type="checkbox" id="{{$category}}" value="@if(isset($permission['time_off_decision']['checked'])){{$permission['time_off_decision']['checked']}}@else{{$permission['time_off_decision'][0]}}@endif" name="employeePermission[]" @if(isset($permission['time_off_decision']['checked'])) checked @endif>
                                                                        <label for="{{$category}}" class="custom-control-label">
                                                                            @if(isset($permission['time_off_decision']['checked']))
                                                                                {{ucwords($permission['time_off_decision']['checked'])}}
                                                                            @else
                                                                                {{ucwords($permission['time_off_decision'][0])}}
                                                                            @endif
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            @else
                                                            <div class="d-flex flex-row">
                                                                <div class="mt-1 flex-fill flex-grow-1 col-md-4">
                                                                    <a class="dropdown-toggle" data-toggle="collapse" href="#{{$group}}{{$category}}Tab" role="button" aria-expanded="false" aria-controls="{{$group}}{{$category}}Tab">
                                                                        <b>{{ucwords (str_replace('_', ' ', $group))}}<i data-feather='chevron-down'></i></b>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="collapse" id="{{$group}}{{$category}}Tab">
                                                                @foreach($permission as $fieldName=>$permissionActions)
                                                                    @php
                                                                        if($category == "time_off")
                                                                            $hasTimeOffTypes=true;
                                                                    @endphp
                                                                <div class="row col-md-12 pl-2">
                                                                    <div class="mt-2 flex-fill flex-grow-1 col-md-3">
                                                                        {{ucwords (str_replace('_', ' ',$fieldName))}}
                                                                    </div>
                                                                    <div class="row col-lg-9 col-md-12 col-sm-12">
                                                                        <div class="mt-2 flex-fill col-md-7 col-sm-4">
                                                                            @php
                                                                            $id = $group.$fieldName;
                                                                            $consumedId =false;
                                                                            @endphp
                                                                            @foreach($permissionActions as $key => $action)
                                                                            @if ($id !== $consumedId)
                                                                            @if (strpos($action,"view")!== false && $key === 'checked')
                                                                            <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                            <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view"> <i data-feather='eye'></i> View Only</p>
                                                                            <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit" style="display: none"> <i data-feather='edit-2'></i> Edit</p>
                                                                            <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval" style="display: none"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                            <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access" style="display: none"> <i data-feather='lock'></i> No Access</p>
                                                                            @php $consumedId = $id; @endphp
                                                                            @elseif(strpos($action,"edit")!== false && $key === 'checked')
                                                                            <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                            <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view" style="display: none"> <i data-feather='eye'></i> View Only</p>
                                                                            <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit"> <i data-feather='edit-2'></i> Edit</p>
                                                                            <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval" style="display: none"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                            <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access" style="display: none"> <i data-feather='lock'></i> No Access</p>
                                                                            @php $consumedId = $id; @endphp
                                                                            @elseif(strpos($action,"edit_with_approval")!== false && $key === 'checked')
                                                                            <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                            <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view" style="display: none"> <i data-feather='eye'></i> View Only</p>
                                                                            <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit" style="display: none"> <i data-feather='edit-2'></i> Edit</p>
                                                                            <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                            <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access" style="display: none"> <i data-feather='lock'></i> No Access</p>
                                                                            @php $consumedId = $id; @endphp
                                                                            @elseif($key !== 'checked')
                                                                            <input type="hidden" id="{{$group}}{{$fieldName}}SelectedValue" style="display: none" @if($key==='checked' ) value="{{$action}}" @endif name="employeePermission[]">
                                                                            <p class="{{$category}}_view_label restrictToViewOnly {{$group}}{{$fieldName}}view" style="display: none"> <i data-feather='eye'></i> View Only</p>
                                                                            <p class="{{$category}}_edit_label restrictToEdit {{$group}}{{$fieldName}}edit" style="display: none"> <i data-feather='edit-2'></i> Edit</p>
                                                                            <p class="{{$category}}_edit_with_approval_label restrictToEditWithApproval {{$group}}{{$fieldName}}edit_with_approval" style="display: none"> <i data-feather='user-check'></i> Edit - Approval Required</p>
                                                                            <p class="{{$category}}_no_access_label restrictToNoAccess {{$group}}{{$fieldName}}no_access"> <i data-feather='lock'></i> No Access</p>
                                                                            @php $consumedId = $id; @endphp
                                                                            @endif
                                                                            @endif
                                                                            @endforeach
                                                                        </div>
                                                                        <div class="mt-1 flex-fill col-md-5 col-sm-8">
                                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                                @php $flag = true; @endphp
                                                                                @foreach($permissionActions as $key => $action)
                                                                                @if($flag )
                                                                                <label class="btn btn-primary p-1 {{$category}}_no_access_button @if($key !== 'checked') active @endif"><i data-feather='lock'></i>
                                                                                    <input type="radio" class="{{$category}}_no_access" access="no_access" id="{{$group}}{{$fieldName}}no_access" value="{{$group}}{{$fieldName}}" permission="" onclick="getPermissionValue(this);" @if($key !=='checked' )checked @endif>
                                                                                </label>
                                                                                @php $flag = false; @endphp
                                                                                @endif
                                                                                @if(strpos($action, 'view') !== false)
                                                                                <label class="btn btn-primary p-1 {{$category}}_view_button @if($key === 'checked') active @endif "><i data-feather='eye'></i>
                                                                                    <input type="radio" class="{{$category}}_view" access="view" id="{{$group}}{{$fieldName}}view" permission="{{$action}}" value="{{$group}}{{$fieldName}}" onclick="getPermissionValue(this);" @if(strpos($action,"view")!==false && $key==='checked' ) checked @endif>
                                                                                </label>
                                                                                @elseif(strpos($action, 'edit ') !== false)
                                                                                <label class="btn btn-primary p-1 {{$category}}_edit_button @if($key === 'checked') active @endif"><i data-feather='edit-2'></i>
                                                                                    <input type="radio" class="{{$category}}_edit" access="edit" id="{{$group}}{{$fieldName}}edit" permission="{{$action}}" value="{{$group}}{{$fieldName}}" onclick="getPermissionValue(this);" @if(strpos($action,"edit")!==false && $key==='checked' ) checked @endif>
                                                                                </label>
                                                                                @elseif(strpos($action, 'edit_with_approval') !== false)
                                                                                <label class="btn btn-primary p-1 {{$category}}_edit_with_approval_button @if($key === 'checked') active @endif"><i data-feather='user-check'></i>
                                                                                    <input type="radio" class="{{$category}}_edit_with_approval" access="edit_with_approval" id="{{$group}}{{$fieldName}}edit_with_approval" permission="{{$action}}" value="{{$group}}{{$fieldName}}" onclick="getPermissionValue(this);" @if(strpos($action,"edit_with_approval")!==false && $key==='checked' ) checked @endif>
                                                                                </label>
                                                                                @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                            @if($category == "time_off")
                                                                @if(!$hasTimeOffTypes)
                                                                    <div class="card m-1">
                                                                        {{--                                                                        <div class="card-body">--}}
                                                                        <div class="p-4 text-center bg-light">
                                                                            <h4 class="text-secondary">No time off types available</h4>
                                                                        </div>
                                                                        {{--                                                                        </div>--}}
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        @php $count++ @endphp
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                                <b>Should Employees be able to see their own information?</b>
                                                <div class="form-check mt-2 ml-1">
                                                    <input class="form-check-input" type="radio" name="hasEmployeeRole" id="yesRadiobtn" value="yes" @if($role->sub_role != null || $subRoleAccessLevel != false) data="yes" checked @else data="no" @endif onclick="toggleAccessLevel(this,{{$employeeRoles}})" >
                                                    <label class="form-check-label" for="yesRadiobtn">
                                                        Yes, Allow Access
                                                    </label>
                                                </div>
                                                <div class="form-check mt-1 ml-1">
                                                    <input class="form-check-input" type="radio" name="hasEmployeeRole" id="noRadiobtn" value="no" @if($role->sub_role == null && $subRoleAccessLevel == false) data="yes" checked @else data="no" @endif onclick="toggleAccessLevel(this,{{$employeeRoles}})">
                                                    <label class="form-check-label" for="noRadiobtn">
                                                        No, Hide the My Info Section
                                                    </label>
                                                </div>
                                                <div id="toggleAccessWithRadioBtn" class="mt-1 ml-1">
                                                    {{-- @if($role->sub_role != null || $subRoleAccessLevel != false) --}}
                                                    Which Access Level should apply?
                                                    <select class="form-control col-md-3 pr-2" id="selectLevel" name="employeeRole">
                                                        @foreach($employeeRoles as $employeeRole)
                                                        <option value="{{$employeeRole->id}}" @if($employeeRole->id == $role->sub_role)
                                                            selected
                                                            @endif >{{$employeeRole->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {{-- @endif --}}
                                                </div>
                                                <div class="row ml-1" id="mtDiv">
                                                    <div class="custom-control custom-checkbox" id="toggleWithFullAccessOption">
                                                        <input class="custom-control-input" type="checkbox" id="canRequestCb" value="option1">
                                                        <label for="canRequestCb" id="canRequestLabel" class="custom-control-label">
                                                            Can Request Time Off
                                                            <span id="toggleWithCanRequestCb">for</span>
                                                        </label>
                                                    </div>
                                                    <div class="dropdown col-12" id="timeOffDropdown">
                                                        <a data-toggle="dropdown" class="btn btn-primary ml-1 dropdown-toggle">
                                                            All Current And Future Time Off Types
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li class="ml-1">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="vacation">
                                                                    <label for="vacation" class="custom-control-label ml-1">
                                                                        Vacation
                                                                    </label>
                                                                </div>
                                                            </li>
                                                            <li class="ml-1 mt-1">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="sick">
                                                                    <label for="sick" class="custom-control-label ml-1">
                                                                        Sick
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                                                <b>Choose which widgets show on Home:</b>
                                                <hr>
                                                <div class="form-group row mt-2 pl-1">
                                                    <div class="row col-md-6 col-sm-12">
                                                        <div class="ml-1 mt-1">
                                                            <div class="custom-control custom-checkbox image-checkbox ">
                                                                <input type="checkbox" class="custom-control-input" id="celebration">
                                                                <label class="custom-control-label" for="celebration">
                                                                    <i class="fas fa-glass-cheers fa-lg text-primary">
                                                                    </i>
                                                                    <br> Celebrations
                                                                </label>
                                                            </div>
                                                            <select class="form-control mr-1" id="celebrationDropdown">
                                                                <option value="">Anniversaries & Birthdays
                                                                </option>
                                                                <option value="">Anniversaries
                                                                </option>
                                                                <option value="">Birthdays
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="ml-1 mt-1">
                                                            <div class="custom-control custom-checkbox image-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="announcement">
                                                                <label class="custom-control-label" for="announcement">
                                                                    <i class="fas fa-bullhorn fa-lg text-primary"></i>
                                                                    <br> Announcements
                                                                </label>
                                                            </div>
                                                            <select class="form-control mr-1" id="announcementDropdown">
                                                                <option value="">View Only
                                                                </option>
                                                                <option value="">Edit
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="ml-1 mt-1">
                                                            <div class="custom-control custom-checkbox image-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="links">
                                                                <label class="custom-control-label" for="links">
                                                                    <i class="fas fa-link  fa-lg text-primary"></i>
                                                                    <br> Links
                                                                </label>
                                                            </div>
                                                            <select class="form-control mr-1" id="linkDropdown">
                                                                <option value="">View Only
                                                                </option>
                                                                <option value="">Edit
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row col-md-6 col-sm-12">
                                                        <div class="custom-control custom-checkbox image-checkbox ml-1 mt-1">
                                                            <input type="checkbox" class="custom-control-input" id="report">
                                                            <label class="custom-control-label" for="report">
                                                                <i class="fas fa-chart-pie fa-lg text-primary"></i>
                                                                <br> Reports
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox image-checkbox ml-1 mt-1">
                                                            <input type="checkbox" class="custom-control-input" id="welcome">
                                                            <label class="custom-control-label" for="welcome">
                                                                <i class="fas fa-id-card fa-lg text-primary"></i>
                                                                <br> Welcome New Hires
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox image-checkbox ml-1 mt-1">
                                                            <input type="checkbox" class="custom-control-input" id="whosOut">
                                                            <label class="custom-control-label" for="whosOut">
                                                                <i class="fas fa-business-time fa-lg text-primary"></i>
                                                                <br> Who's Out
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-1 mr-1">
                                                    Pssst... the Benefits, Goals, and Training widgets will show if there is an active item in them. The What’s Happening widget always
                                                    shows.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="ml-1 mr-1">
                    <div class="col-md-12 col-sm-12" style="overflow:auto;">
                        <button type="button" class="btn btn-outline-warning waves-effect waves-float waves-light mt-1 mb-1" id="cancelBtn" onclick="window.location.href='@if(isset($locale)){{url($locale.'/access-level')}} @else {{url('en/access-level')}} @endif'"><i class="d-block d-md-none" data-feather='x-circle'></i><span class="d-none d-md-inline"> Cancel</span></button>
                        <button type="button" class="btn btn-primary ml-1 mt-1 mb-1 waves-effect waves-float waves-light" id="nextBtn" onclick="nextPrev(1)" style="float: right;"><span class="d-none d-md-inline">Next </span><i data-feather='chevron-right'></i></button>
                        <button type="button" class="btn btn-primary ml-1 mt-1 mb-1 waves-effect waves-float waves-light" id="submitBtn" onclick="submitForm()" style="float: right;"><i class="d-block d-md-none" data-feather='check-circle'></i><span class="d-none d-md-inline"> Save</span></button>
                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light ml-1 mt-1 mb-1" id="prevBtn" onclick="nextPrev(-1)" style="float: right;"><i data-feather='chevron-left'></i><span class="d-none d-md-inline" id="nextIcon"> Previous</span></button>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </form>
    </div>


    <!--end::Portlet-->

</div>
<script>
    function myFunction(permission) {
        var i = 0;
        while (permission[i] != null) {
            console.log(i);
            console.log(permission[i]);
            access = $(permission[i]).attr('access');
            // console.log(access);
            no_access = '.' + permission[i].value + 'no_access';
            view = '.' + permission[i].value + 'view';
            edit = '.' + permission[i].value + 'edit';
            edit_with_approval = '.' + permission[i].value + 'edit_with_approval';
            selected = '#' + permission[i].value + 'SelectedValue';
            // console.log(permission[i]);
            if (access == 'view') {
                console.log('view');
                $(no_access).hide();
                $(view).show();
                $(edit).hide();
                $(edit_with_approval).hide();
                perm = $(permission[i]).attr('permission');
                $(selected).val(perm);
            } else if (access == 'no_access') {
                console.log('no_access');
                $(no_access).show();
                $(view).hide();
                $(edit).hide();
                $(edit_with_approval).hide();
                perm = $(permission[i]).attr('permission');
                $(selected).val(perm);

            } else if (access == 'edit_with_approval') {
                console.log('edit with approval');
                $(no_access).hide();
                $(view).hide();
                $(edit).hide();
                $(edit_with_approval).show();
                perm = $(permission[i]).attr('permission');
                $(selected).val(perm);

            } else if (access == 'edit') {
                console.log('edit');
                $(no_access).hide();
                $(view).hide();
                $(edit).show();
                $(edit_with_approval).hide();
                perm = $(permission[i]).attr('permission');
                $(selected).val(perm);
            }
            i++;
        }
    }

    $(".set_all").on("click", function() {
        access = $(this).attr('value');
        category = $(this).attr('category');
        selected = $('.' + category + '_' + access);
        console.log(access);
        console.log(selected);
        console.log(category);
        myFunction(selected);
        if (access == 'view') {
            $('.' + category + '_no_access_label').hide();
            $('.' + category + '_edit_label').hide();
            $('.' + category + '_edit_with_approval_label').hide();
            $('.' + category + '_view_label').show();

            $('.' + category + '_no_access').prop('checked', false);
            $('.' + category + '_edit').prop('checked', false);
            $('.' + category + '_edit_with_approval').prop('checked', false);
            $('.' + category + '_view').prop('checked', true);

            $('.' + category + '_no_access_button').removeClass('active');
            $('.' + category + '_edit_button').removeClass('active');
            $('.' + category + '_edit_with_approval_button').removeClass('active');
            $('.' + category + '_view_button').addClass('active');
        } else if (access == 'no_access') {
            $('.' + category + '_edit_label').hide();
            $('.' + category + '_edit_with_approval_label').hide();
            $('.' + category + '_view_label').hide();
            $('.' + category + '_no_access_label').show();

            $('.' + category + '_edit').prop('checked', false);
            $('.' + category + '_edit_with_approval').prop('checked', false);
            $('.' + category + '_view').prop('checked', false);
            $('.' + category + '_no_access').prop('checked', true);

            $('.' + category + '_view_button').removeClass('active');
            $('.' + category + '_edit_button').removeClass('active');
            $('.' + category + '_edit_with_approval_button').removeClass('active');
            $('.' + category + '_no_access_button').addClass('active');
        } else if (access == 'edit_wit') {

        }
        // console.log(data);
        // console.log(category);
    });

    var currentTab = 0; // Current tab is set to be the first tab (0)
    // showTab(currentTab); // Display the current tab
    document.getElementById("prevBtn").style.display = "none";
    document.getElementById("submitBtn").style.display = "none";

    function showTab(n) {
        // This function will display the specified tab of the form ...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        // ... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }

        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").style.display = "none";
            document.getElementById("submitBtn").style.display = "inline";
        } else {
            document.getElementById("submitBtn").style.display = "none";
            document.getElementById("nextBtn").style.display = "inline";
        }
        // ... and run a function that displays the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;

        // display the correct tab:
        showTab(currentTab);
    }

    function submitForm()
    {
        if (!validateForm()) return false;

        document.getElementById("managerRole").submit();

    }

    function validateForm() {
        // This function deals with validation of the form fields
        var valid = true;
        if ($("#access_level_name").val() == '') {
            $("#access_level_name").addClass('error')
            $(".invalid-feedback").show();
            valid = false;
        } else {
            $("#access_level_name").removeClass('error')
            $(".invalid-feedback").hide();
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }

    // function toggleAccessLevel(input, employeesRole) {
    //     if (input.value == 'yes') {

    //         $("#toggleAccessWithRadioBtn").append(
    //             '    Which Access Level should apply?\n' +
    //             '   <select class="form-control col-md-3" id="selectLevel" name="employeeRole">\n' +
    //             '    </select>'
    //         );
    //         $.each(employeesRole, function(index, role) {
    //             $("#selectLevel").append(
    //                 '        <option value=' + role.id + '>' + role.name + '</option>'
    //             );
    //         });
    //     } else {
    //         $("#toggleAccessWithRadioBtn").empty();
    //     }
    // }

    $(document).ready(function() {
        console.log('hi');
        if ($('#yesRadiobtn').attr('data') == "yes") {
            console.log('yesRadiobtn');
            $('#toggleAccessWithRadioBtn').show();
            if ($("#selectLevel option:selected").val() == "full") {
                $("#toggleWithFullAccessOption").addClass('mt-2');
                $("#toggleWithFullAccessOption").show();
            }
        }
        if ($('#noRadiobtn').attr('data') == 'yes') {
            console.log('noRadiobtn');
            $('#toggleAccessWithRadioBtn').hide();
            $("#toggleWithFullAccessOption").hide();
            $("#timeOffDropdown").hide();
        }
    });
    //hide dropdoown if no is checked
    $('#noRadiobtn').click(function() {
        $(this).prop('checked', true);
        $('#toggleAccessWithRadioBtn').hide();
        $("#toggleWithFullAccessOption").hide();
        $("#timeOffDropdown").hide();
    });
    //show dropdoown if no is checked
    $('#yesRadiobtn').click(function() {
        $(this).prop('checked', true);
        $('#selectLevel').prop('style', "");
        $('#toggleAccessWithRadioBtn').show();
        if ($("#selectLevel option:selected").val() == "full") {
            $("#toggleWithFullAccessOption").addClass('mt-2');
            $("#toggleWithFullAccessOption").show();
        }
    });
    //dropdown option selected
    $("#toggleWithFullAccessOption").hide();
    $("#selectLevel").change(function() {
        var val = $(this).val();
        if (val == "full") {
            document.getElementById("mtDiv").addClass('mt-2');
            $("#toggleWithFullAccessOption").show();
        } else {
            $("#toggleWithFullAccessOption").hide();
        }
    });
    //Request Time off checkbox
    $("#timeOffDropdown").hide();
    $('#toggleWithCanRequestCb').hide();
    $("#canRequestCb").change(function() {
        if (!$(this).is(':checked')) {
            $('#toggleWithCanRequestCb').hide();
            $("#timeOffDropdown").hide();
        } else {
            $('#toggleWithCanRequestCb').show();
            $("#timeOffDropdown").show();
        }
    });
     //Show/Hide Attendance Permission on "Can view employee attendance" checkbox change
     $('.requestAttendance').change(function() {
        console.log('hi');
        if (this.checked) {
            $('.attendanceList').show();

        } else {
            $('.myCheckbox').prop('checked', false);
            $('.attendanceList').hide();
        }

    });
    //See on Home checkboxes
    $("#celebrationDropdown").hide();
    $("#announcementDropdown").hide();
    $("#linkDropdown").hide();

    $("#celebration").click(function() {
        $("#celebrationDropdown").toggle();
    });
    $("#announcement").click(function() {
        $("#announcementDropdown").toggle();
    });
    $("#links").click(function() {
        $("#linkDropdown").toggle();
    });
    //see a other employee 'personal' tab
    $('#restrictToViewOnly').hide();
    $('#noAccessBasicInfo').change(function() {
        $('#restrictToNoAccess').show();
        $('#restrictToViewOnly').hide();
    });
    $('#canViewBasicInfo').change(function() {
        $('#restrictToNoAccess').hide();
        $('#restrictToViewOnly').show();
    });

    function getPermissionValue(permission) {
        console.log(permission);
        no_access = '.' + permission.value + 'no_access';
        view = '.' + permission.value + 'view';
        edit = '.' + permission.value + 'edit';
        edit_with_approval = '.' + permission.value + 'edit_with_approval';
        selected = '#' + permission.value + 'SelectedValue';
        console.log(permission);
        if (permission.id.includes('no_access')) {
            console.log('no_access');
            $(no_access).show();
            $(view).hide();
            $(edit).hide();
            $(edit_with_approval).hide();
            perm = $(permission).attr('permission');
            $(selected).val(perm);
        } else if (permission.id.includes('view')) {
            console.log('view');
            $(no_access).hide();
            $(view).show();
            $(edit).hide();
            $(edit_with_approval).hide();
            perm = $(permission).attr('permission');
            $(selected).val(perm);

        } else if (permission.id.includes('edit_with_approval')) {
            console.log('edit with');
            $(no_access).hide();
            $(view).hide();
            $(edit).hide();
            $(edit_with_approval).show();
            perm = $(permission).attr('permission');
            $(selected).val(perm);

        } else if (permission.id.includes('edit')) {
            console.log('edit');
            $(no_access).hide();
            $(view).hide();
            $(edit).show();
            $(edit_with_approval).hide();
            perm = $(permission).attr('permission');
            $(selected).val(perm);
        }
    }
</script>
@stop