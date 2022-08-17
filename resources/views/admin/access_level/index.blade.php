@extends('layouts/contentLayoutMaster')

@section('title','Access Level')

@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')


<!-- /.content-header -->
<div class="">
    <div class="card card-primary card-outline card-tabs">
        <div class="dropdown ml-1 mt-1">
            <!-- <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather='settings'></i>
                </a> -->
            <div class="btn-group mr-1">
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather='plus-circle' class="font-medium-2"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/employee/create')}}
                            @else {{url('en/access-level/employee/create')}} @endif">Employee Access Level
                        <span class="d-none d-md-inline">
                            <p class="h6">For Employees who should never see sensitive<br> information for anyone but themselves.</p>
                        </span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/manager/create')}}
                            @else {{url('en/access-level/manager/create')}} @endif">Manager Access Level
                        <span class="d-none d-md-inline">
                            <p class="h6">For Employees who can only see information<br> for indirect and/or direct reports.</p>
                        </span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/custom/create')}}
                            @else {{url('en/access-level/custom/create')}} @endif">Custom Access Level
                        <span class="d-none d-md-inline">
                            <p class="h6">For Employees who can see and/or edit <br>information for some or all employees.</p>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <hr class="ml-1 mr-1">
        <div class=" p-0 pl-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                    <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">ALL</a>
                </li>
                <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                    <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Full Admin</a>
                </li>
                <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                    <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Employee</a>
                </li>
                <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                    <a class="nav-link" id="custom-tabs-two-manager" data-toggle="pill" href="#custom-tabs-manager" role="tab" aria-controls="custom-tabs-manager" aria-selected="false">Manager</a>
                </li>
                <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                    <a class="nav-link" id="custom-tabs-two-custom" data-toggle="pill" href="#custom-tabs-custom" role="tab" aria-controls="custom-tabs-custom" aria-selected="false">Custom</a>
                </li>
                <li class="nav-item col-lg-2 col-md-2 col-sm-12">
                    <a class="nav-link" id="custom-tabs-two-no-access" data-toggle="pill" href="#custom-tabs-no-access" role="tab" aria-controls="custom-tabs-no-access" aria-selected="false">No Access</a>
                </li>
            </ul>
        </div>
        <div class="ml-1 mr-1">
            <div class="tab-content" id="custom-tabs-two-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="">
                                <div class="card-datatable table-responsive">
                                    {{-- @if($accessLevelEmployees['allEmployees']->count() > 0) --}}
                                    <table class="dt-simple-header table dataTable dtr-column">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th> {{__('language.Employee Name')}}</th>
                                                <th> {{__('language.Level')}}</th>
                                                <th> {{__('language.Last Login')}}</th>
                                                <th> {{__('language.Actions')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($accessLevelEmployees['allEmployees'] as $key => $employee)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                                <td>
                                                    @foreach ($employee->getRoleNames() as $roleName)
                                                    {{$roleName}}<br>
                                                    @endforeach
                                                </td>
                                                <td class="text-nowrap">{{\Carbon\Carbon::parse($employee->last_login)->format("M d,Y h:i A")}}</td>
                                                <td>
                                                    @if ($employee->designation != 'admin')
                                                    <div class="col-md-6">
                                                        <div class="btn-group">
                                                            <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                <i data-feather='settings'></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                <div class="form-group">
                                                                    @foreach($adminRole as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}} @endif">
                                                                        {{__('language.Full Admin')}}
                                                                    </a>
                                                                    @endforeach
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$employee->id)}}
                                                                                @else {{url('en/access-levels/addemployee/noaccess/role/'.$employee->id)}} @endif">
                                                                        {{__('language.No Access')}}
                                                                    </a>
                                                                    @if($employeeRoles->isNotEmpty())
                                                                    <div class="dropdown-divider"></div>
                                                                    <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                    @foreach($employeeRoles as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}} @endif">
                                                                        {{$role->name}}
                                                                    </a>
                                                                    @endforeach
                                                                    @endif
                                                                    @if($managerRoles->isNotEmpty())
                                                                    <div class="dropdown-divider"></div>
                                                                    <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                    @foreach($managerRoles as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}} @endif">
                                                                        {{$role->name}}
                                                                    </a>
                                                                    @endforeach
                                                                    @endif
                                                                    @if($customRoles->isNotEmpty())
                                                                    <div class="dropdown-divider"></div>
                                                                    <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                    @foreach($customRoles as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}} @endif">
                                                                        {{$role->name}}
                                                                    </a>
                                                                    @endforeach
                                                                    @endif
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                        Multiple Access Levels...
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- @else --}}
                                    {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                    {{-- @endif --}}
                                    <!--end: Datatable -->
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                    @foreach($accessLevelAdmin['roles'] as $key => $role)
                    @if($key == 0)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="pt-1">
                                    <div class="row mb-2">
                                        <div class="col-md-12 col-sm-12 mt-1">
                                            <a class="btn btn-primary" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-levels/addemployee/'.$role->id.'/edit')}} @endif">
                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline"> Add Employee</span>
                                            </a>
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather='settings'></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}} @else {{url('en/access-levels/create-non-employee')}} @endif">
                                                    Add a Non-Employee User
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-datatable table-responsive">
                                        {{-- @if($accessLevelEmployees['admin']->count() > 0) --}}
                                        <table class="dt-simple-header table dataTable dtr-column">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th> {{__('language.Employee Name')}}</th>
                                                    <th> {{__('language.Last Login')}}</th>
                                                    <th> {{__('language.Actions')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($accessLevelEmployees['admin'] as $key => $admin)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$admin->firstname}}{{$admin->lastname}}</td>
                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                    <td>
                                                        @if ($admin->designation != 'admin')
                                                        <div class="col-md-6">
                                                            <div class="btn-group">
                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                    <i data-feather='settings'></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                    <div class="form-group">
                                                                        @foreach($adminRole as $role)
                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                            {{__('language.Full Admin')}}
                                                                        </a>
                                                                        @endforeach
                                                                        <div class="dropdown-divider"></div>
                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                    @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                            {{__('language.No Access')}}
                                                                        </a>
                                                                        @if($employeeRoles->isNotEmpty())
                                                                        <div class="dropdown-divider"></div>
                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                        @foreach($employeeRoles as $role)
                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                            {{$role->name}}
                                                                        </a>
                                                                        @endforeach
                                                                        @endif
                                                                        @if($managerRoles->isNotEmpty())
                                                                        <div class="dropdown-divider"></div>
                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                        @foreach($managerRoles as $role)
                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                            {{$role->name}}
                                                                        </a>
                                                                        @endforeach
                                                                        @endif
                                                                        @if($customRoles->isNotEmpty())
                                                                        <div class="dropdown-divider"></div>
                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                        @foreach($customRoles as $role)
                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                            {{$role->name}}
                                                                        </a>
                                                                        @endforeach
                                                                        @endif
                                                                        <div class="dropdown-divider"></div>
                                                                        <a class="dropdown-item" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                            Multiple Access Levels...
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{-- @else --}}
                                        {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                        {{-- @endif --}}
                                        <!--end: Datatable -->
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                    @if($accessLevelEmployees['roles']->isEmpty())
                        <div class="text-center mt-2 mb-1">
                            <h4 class="text-secondary"> There is no employee role please add one</h4>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="nav nav-pills flex-column h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                @if(!$accessLevelEmployees['roles']->isEmpty())
                                    <label class="h5 mb-1" style="font-size:auto;"><u><b>Employee access type:</b></u></label>
                                @endif
                                @foreach($accessLevelEmployees['roles'] as $key => $role)
                                @if($key == 0)
                                <a class="nav-link active" id="vert-tabs-employee-{{$role->id}}-tab" data-toggle="pill" href="#vert-tabs-employee-{{$role->id}}" role="tab" aria-controls="vert-tabs-employee-{{$role->id}}" aria-selected="true">
                                    {{$role->name}}
                                </a>
                                @else
                                <a class="nav-link" id="vert-tabs-employee-{{$role->id}}-tab" data-toggle="pill" href="#vert-tabs-employee-{{$role->id}}" role="tab" aria-controls="vert-tabs-employee-{{$role->id}}" aria-selected="true">
                                    {{$role->name}}
                                </a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 col-md-9 col-sm-12">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                @foreach($accessLevelEmployees['roles'] as $key => $role)
                                @if($key == 0)
                                <div class="tab-pane text-left fade show active" id="vert-tabs-employee-{{$role->id}}" role="tabpanel" aria-labelledby="vert-tabs-employee-{{$role->id}}-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="pt-1">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12 col-sm-12">
                                                            <a class="btn btn-primary mt-1" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-level/addemployee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline"> Add Employee</span>
                                                            </a>
                                                            <a class="btn btn-secondary mt-1" href="@if(isset($locale)){{url($locale.'/access-level/employee/'.$role->id.'/edit')}} @else {{url('en/access-level/employee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='edit'></i><span class="d-none d-md-inline"> Access Level Settings</span>
                                                            </a>
                                                            <button type="button" class="btn btn-primary mt-1 dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather='settings'></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}}@else{{url('en/access-levels/create-non-employee')}}@endif">
                                                                    Add a Non-Employee
                                                                </a>
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/employee/duplicate',$role->id)}} @else {{url('en/access-level/employee/duplicate',$role->id)}} @endif">
                                                                    Duplicate Access Level
                                                                </a>
                                                                <form id="delete_role_{{$role->id}}" action="@if(isset($locale)){{url($locale.'/access-level/employee',$role->id)}}
                                                                    @else {{url('en/access-level/employee',$role->id)}} @endif" method="post">
                                                                    @method('DELETE')
                                                                    {{ csrf_field() }}
                                                                    <a class="dropdown-item" href="javascript:$('#delete_role_{{$role->id}}').submit();">
                                                                        Delete Access level
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-datatable table-responsive">
                                                        {{-- @if($accessLevelEmployees[$role->name]->count() > 0) --}}
                                                        <table class="dt-simple-header table dataTable dtr-column">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th> {{__('language.Employee Name')}}</th>
                                                                    <th> {{__('language.Last Login')}}</th>
                                                                    <th> {{__('language.Actions')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($accessLevelEmployees[$role->name] as $key => $admin)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>{{$admin->firstname}} {{$admin->lastname}}</td>
                                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                                    <td>
                                                                        <div class="col-md-6">
                                                                            <div class="btn-group">
                                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                                    <i data-feather='settings'></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                                    <div class="form-group">
                                                                                        @foreach($adminRole as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.Full Admin')}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                            @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.No Access')}}
                                                                                        </a>
                                                                                        @if($employeeRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                                        @foreach($employeeRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($managerRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                                        @foreach($managerRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($customRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                                        @foreach($customRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                        @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                                            Multiple Access Levels...
                                                                                        </a> 
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        {{-- @else --}}
                                                        {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                                        {{-- @endif --}}
                                                        <!--end: Datatable -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Portlet-->
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="tab-pane fade" id="vert-tabs-employee-{{$role->id}}" role="tabpanel" aria-labelledby="vert-tabs-employee-{{$role->id}}-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="pt-1">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12 col-sm-12">
                                                            <a class="btn btn-primary mt-1" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-level/addemployee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline"> Add Employee</span>
                                                            </a>
                                                            <a class="btn btn-secondary mt-1" href="@if(isset($locale)){{url($locale.'/access-level/employee/'.$role->id.'/edit')}} @else {{url('en/access-level/employee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='edit'></i><span class="d-none d-md-inline"> Access Level Settings</span>
                                                            </a>
                                                            <button type="button" class="btn btn-primary mt-1 dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float:right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather='settings'></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}}@else{{url('en/access-levels/create-non-employee')}}@endif">
                                                                    Add a Non-Employee
                                                                </a>
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/employee/duplicate',$role->id)}} @else {{url('en/access-level/employee/duplicate',$role->id)}} @endif">
                                                                    Duplicate Access Level
                                                                </a>
                                                                <form id="delete_role_{{$role->id}}" action="@if(isset($locale)){{url($locale.'/access-level/employee',$role->id)}} @else {{url('en/access-level/employee',$role->id)}} @endif" method="post">
                                                                    @method('DELETE')
                                                                    {{ csrf_field() }}
                                                                    <a class="dropdown-item" href="javascript:$('#delete_role_{{$role->id}}').submit();">
                                                                        Delete Access level
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-datatable table-responsive">
                                                        {{-- @if($accessLevelEmployees[$role->name]->count() > 0) --}}
                                                        <table class="dt-simple-header table dataTable dtr-column">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th> {{__('language.Employee Name')}}</th>
                                                                    <th> {{__('language.Last Login')}}</th>
                                                                    <th> {{__('language.Actions')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($accessLevelEmployees[$role->name] as $key => $admin)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>{{$admin->firstname}} {{$admin->lastname}}</td>
                                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                                    <td>
                                                                        <div class="col-md-6">
                                                                            <div class="btn-group">
                                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                                    <i data-feather='settings'></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                                    <div class="form-group">
                                                                                        @foreach($adminRole as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.Full Admin')}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.No Access')}}
                                                                                        </a>
                                                                                        @if($employeeRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                                        @foreach($employeeRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($managerRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                                        @foreach($managerRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($customRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                                        @foreach($customRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                                            Multiple Access Levels...
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        {{-- @else --}}
                                                        {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                                        {{-- @endif --}}
                                                        <!--end: Datatable -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Portlet-->
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-manager" role="tabpanel" aria-labelledby="custom-tabs-manager-tab">
                    @if($accessLevelManager['roles']->isEmpty())
                        <div class="text-center mt-2 mb-1">
                            <h4 class="text-secondary"> There is no manager role please add one</h4>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="nav nav-pills flex-column h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                @if(!$accessLevelManager['roles']->isEmpty())
                                    <label class="h5 mb-1" style="font-size:auto;"><u><b>Manager access type:</b></u></label>
                                @endif
                                @foreach($accessLevelManager['roles'] as $key => $role)
                                @if($key == 0)
                                <a class="nav-link active" id="vert-tabs-manager-{{$role->id}}-tab" data-toggle="pill" href="#vert-tabs-manager-{{$role->id}}" role="tab" aria-controls="vert-tabs-manager-{{$role->id}}" aria-selected="true">{{$role->name}}</a>
                                @else
                                <a class="nav-link" id="vert-tabs-manager-{{$role->id}}-tab" data-toggle="pill" href="#vert-tabs-manager-{{$role->id}}" role="tab" aria-controls="vert-tabs-manager-{{$role->id}}" aria-selected="true">{{$role->name}}</a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 col-md-9 col-sm-12">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                @foreach($accessLevelManager['roles'] as $key => $role)
                                @if($key == 0)
                                <div class="tab-pane text-left fade show active" id="vert-tabs-manager-{{$role->id}}" role="tabpanel" aria-labelledby="vert-tabs-manager-{{$role->id}}-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="pt-1">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12 col-sm-12">
                                                            <a class="btn btn-primary mt-1" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-level/addemployee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline"> Add Employee</span>
                                                            </a>
                                                            <a class="btn btn-secondary mt-1" href="@if(isset($locale)){{url($locale.'/access-level/manager/'.$role->id.'/edit')}}
                                                            @else {{url('en/access-level/manager/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='edit'></i><span class="d-none d-md-inline"> Access Level Settings</span>
                                                            </a>
                                                            <button type="button" class="btn btn-primary mt-1 dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather='settings'></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}}@else{{url('en/access-levels/create-non-employee')}}@endif">
                                                                    Add a Non-Employee
                                                                </a>
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/manager/duplicate',$role->id)}}
                                                                    @else {{url('en/access-level/manager/duplicate',$role->id)}} @endif">Duplicate Access Level</a>
                                                                <form id="delete_role_{{$role->id}}" action="@if(isset($locale)){{url($locale.'/access-level/manager',$role->id)}}
                                                                    @else {{url('en/access-level/manager',$role->id)}} @endif" method="post">
                                                                    @method('DELETE')
                                                                    {{ csrf_field() }}
                                                                    <a class="dropdown-item" href="javascript:$('#delete_role_{{$role->id}}').submit();">Delete Access level</a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-datatable table-responsive">
                                                        {{-- @if($accessLevelManager[$role->name]->count() > 0) --}}
                                                        <table class="dt-simple-header table dataTable dtr-column">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th> {{__('language.Employee Name')}}</th>
                                                                    <th> {{__('language.Last Login')}}</th>
                                                                    <th> {{__('language.Actions')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($accessLevelManager[$role->name] as $key => $admin)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>{{$admin->firstname}} {{$admin->lastname}}</td>
                                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                                    <td>
                                                                        <div class="col-md-6">
                                                                            <div class="btn-group">
                                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                                    <i data-feather='settings'></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                                    <div class="form-group">
                                                                                        @foreach($adminRole as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.Full Admin')}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.No Access')}}
                                                                                        </a>
                                                                                        @if($employeeRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                                        @foreach($employeeRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($managerRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                                        @foreach($managerRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                    @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($customRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                                        @foreach($customRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                        @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                                            Multiple Access Levels...
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        {{-- @else --}}
                                                        {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                                        {{-- @endif --}}
                                                        <!--end: Datatable -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Portlet-->
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="tab-pane fade" id="vert-tabs-manager-{{$role->id}}" role="tabpanel" aria-labelledby="vert-tabs-manager-{{$role->id}}-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="pt-1">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12 col-sm-12">
                                                            <a class="btn btn-primary mt-1" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-level/addemployee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline"> Add Employee</span>
                                                            </a>
                                                            <a class="btn btn-secondary mt-1" href="@if(isset($locale)){{url($locale.'/access-level/manager/'.$role->id.'/edit')}}
                                                            @else {{url('en/access-level/manager/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='edit'></i><span class="d-none d-md-inline"> Access Level Settings</span>
                                                            </a>
                                                            <button type="button" class="btn btn-primary mt-1 dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather='settings'></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}}@else{{url('en/access-levels/create-non-employee')}}@endif">
                                                                    Add a Non-Employee
                                                                </a>
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/manager/duplicate',$role->id)}}
                                                                @else {{url('en/access-level/manager/duplicate',$role->id)}} @endif">Duplicate Access Level</a>
                                                                <form id="delete_role_{{$role->id}}" action="@if(isset($locale)){{url($locale.'/access-level/manager',$role->id)}}
                                                                    @else {{url('en/access-level/manager',$role->id)}} @endif" method="post">
                                                                    @method('DELETE')
                                                                    {{ csrf_field() }}
                                                                    <a class="dropdown-item" href="javascript:$('#delete_role_{{$role->id}}').submit();">Delete Access level</a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-datatable table-responsive">
                                                        {{-- @if($accessLevelManager[$role->name]->count() > 0) --}}
                                                        <table class="dt-simple-header table dataTable dtr-column">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th> {{__('language.Employee Name')}}</th>
                                                                    <th> {{__('language.Last Login')}}</th>
                                                                    <th> {{__('language.Actions')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($accessLevelManager[$role->name] as $key => $admin)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>{{$admin->firstname}} {{$admin->lastname}}</td>
                                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                                    <td>
                                                                        <div class="col-md-6">
                                                                            <div class="btn-group">
                                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                                    <i data-feather='settings'></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                                    <div class="form-group">
                                                                                        @foreach($adminRole as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.Full Admin')}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                            @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.No Access')}}
                                                                                        </a>
                                                                                        @if($employeeRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                                        @foreach($employeeRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($managerRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                                        @foreach($managerRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($customRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                                        @foreach($customRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                                            Multiple Access Levels...
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        {{-- @else --}}
                                                        {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                                        {{-- @endif --}}
                                                        <!--end: Datatable -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Portlet-->
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-custom" role="tabpanel" aria-labelledby="custom-tabs-custom">
                    @if($accessLevelCustom['roles']->isEmpty())
                        <div class="text-center mt-2 mb-1">
                            <h4 class="text-secondary"> There is no custom role please add one</h4>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="nav nav-pills flex-column h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                @if(!$accessLevelCustom['roles']->isEmpty())
                                    <label class="h5 mb-1" style="font-size:auto;"><u><b>Custom access type:</b></u></label>
                                @endif
                                @foreach($accessLevelCustom['roles'] as $key => $role)
                                @if($key == 0)
                                <a class="nav-link active" id="vert-tabs-custom-{{$role->id}}-tab" data-toggle="pill" href="#vert-tabs-custom-{{$role->id}}" role="tab" aria-controls="vert-tabs-custom-{{$role->id}}" aria-selected="true">{{$role->name}}</a>
                                @else
                                <a class="nav-link" id="vert-tabs-custom-{{$role->id}}-tab" data-toggle="pill" href="#vert-tabs-custom-{{$role->id}}" role="tab" aria-controls="vert-tabs-custom-{{$role->id}}" aria-selected="true">{{$role->name}}</a>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 col-md-9 col-sm-12">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                @foreach($accessLevelCustom['roles'] as $key => $role)
                                @if($key == 0)
                                <div class="tab-pane text-left fade show active" id="vert-tabs-custom-{{$role->id}}" role="tabpanel" aria-labelledby="vert-tabs-custom-{{$role->id}}-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="pt-1">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12 col-sm-12">
                                                            <a class="btn btn-primary mt-1" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-level/addemployee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline"> Add Employee</span>
                                                            </a>
                                                            <a class="btn btn-secondary mt-1" href="@if(isset($locale)){{url($locale.'/access-level/custom/'.$role->id.'/edit')}}
                                                            @else {{url('en/access-level/custom/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='edit'></i><span class="d-none d-md-inline"> Access Level Settings</span>
                                                            </a>
                                                            <button type="button" class="btn btn-primary mt-1 dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather='settings'></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}}@else{{url('en/access-levels/create-non-employee')}}@endif">
                                                                    Add a Non-Employee
                                                                </a>
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/custom/duplicate',$role->id)}} @else {{url('en/access-level/custom/duplicate',$role->id)}} @endif">
                                                                    Duplicate Access Level
                                                                </a>
                                                                <form id="delete_role_{{$role->id}}" action="@if(isset($locale)){{url($locale.'/access-level/custom',$role->id)}}
                                                                @else {{url('en/access-level/custom',$role->id)}} @endif" method="post">
                                                                    @method('DELETE')
                                                                    {{ csrf_field() }}
                                                                    <a class="dropdown-item" href="javascript:$('#delete_role_{{$role->id}}').submit();">
                                                                        Delete Access level
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-datatable table-responsive">
                                                        {{-- @if($accessLevelCustom[$role->name]->count() > 0) --}}
                                                        <table class="dt-simple-header table dataTable dtr-column">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th> {{__('language.Employee Name')}}</th>
                                                                    <th> {{__('language.Last Login')}}</th>
                                                                    <th> {{__('language.Actions')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($accessLevelCustom[$role->name] as $key => $admin)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>{{$admin->firstname}} {{$admin->lastname}}</td>
                                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                                    <td>
                                                                        <div class="col-md-6">
                                                                            <div class="btn-group">
                                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                                    <i data-feather='settings'></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                                    <div class="form-group">
                                                                                        @foreach($adminRole as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.Full Admin')}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                            @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.No Access')}}
                                                                                        </a>
                                                                                        @if($employeeRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                                        @foreach($employeeRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($managerRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                                        @foreach($managerRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                            @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($customRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                                        @foreach($customRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                                            Multiple Access Levels...
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        {{-- @else --}}
                                                        {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                                        {{-- @endif --}}
                                                        <!--end: Datatable -->
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Portlet-->
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="tab-pane fade" id="vert-tabs-custom-{{$role->id}}" role="tabpanel" aria-labelledby="vert-tabs-custom-{{$role->id}}-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="pt-1">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12 col-sm-12">
                                                            <a class="btn btn-primary mt-1" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/edit')}} @else {{url('en/access-level/addemployee/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='user-plus'></i><span class="d-none d-md-inline">Add Employee</span>
                                                            </a>
                                                            <a class="btn btn-secondary mt-1" href="@if(isset($locale)){{url($locale.'/access-level/custom/'.$role->id.'/edit')}}
                                                            @else {{url('en/access-level/custom/'.$role->id.'/edit')}} @endif">
                                                                <i data-feather='edit'></i><span class="d-none d-md-inline"> Access Level Settings</span>
                                                            </a>
                                                            <button type="button" class="btn btn-primary mt-1 dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" style="float: right;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather='settings'></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/create-non-employee')}}@else{{url('en/access-levels/create-non-employee')}}@endif">
                                                                    Add a Non-Employee
                                                                </a>
                                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-level/custom/duplicate',$role->id)}} @else {{url('en/access-level/custom/duplicate',$role->id)}} @endif">
                                                                    Duplicate Access Level
                                                                </a>
                                                                <form id="delete_role_{{$role->id}}" action="@if(isset($locale)){{url($locale.'/access-level/custom',$role->id)}}
                                                                @else {{url('en/access-level/custom',$role->id)}} @endif" method="post">
                                                                    @method('DELETE')
                                                                    {{ csrf_field() }}
                                                                    <a class="dropdown-item" href="javascript:$('#delete_role_{{$role->id}}').submit();">
                                                                        Delete Access level
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-datatable table-responsive">
                                                        {{-- @if($accessLevelCustom[$role->name]->count() > 0) --}}
                                                        <table class="dt-simple-header table dataTable dtr-column">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th> {{__('language.Employee Name')}}</th>
                                                                    <th> {{__('language.Last Login')}}</th>
                                                                    <th> {{__('language.Actions')}}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($accessLevelCustom[$role->name] as $key => $admin)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>{{$admin->firstname}} {{$admin->lastname}}</td>
                                                                    <td class="text-nowrap">{{\Carbon\Carbon::parse($admin->last_login)->format("M d,Y h:i A")}}</td>
                                                                    <td>
                                                                        <div class="col-md-6">
                                                                            <div class="btn-group">
                                                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                                    <i data-feather='settings'></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                                    <div class="form-group">
                                                                                        @foreach($adminRole as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.Full Admin')}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$admin->id)}}
                                                                                            @else {{url('en/access-levels/addemployee/noaccess/role/'.$admin->id)}} @endif">
                                                                                            {{__('language.No Access')}}
                                                                                        </a>
                                                                                        @if($employeeRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                                        @foreach($employeeRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($managerRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                                        @foreach($managerRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        @if($customRoles->isNotEmpty())
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                                        @foreach($customRoles as $role)
                                                                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}}
                                                                                                @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                                            {{$role->name}}
                                                                                        </a>
                                                                                        @endforeach
                                                                                        @endif
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                                            Multiple Access Levels...
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Portlet-->
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-no-access" role="tabpanel" aria-labelledby="custom-tabs-no-access">
                    <div class="row">
                        {{-- @if($noAccessEmployees->isEmpty()) --}}
                        {{-- <h3>No access is empty</h3> --}}
                        {{-- @endif --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <b style="color: red;">Employees with this level have no access and are unable to login.</b>
                                <div class="card-datatable table-responsive">
                                    {{-- @if($accessLevelEmployees['admin']->count() > 0) --}}
                                    <table class="dt-simple-header table dataTable dtr-column">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th> {{__('language.Employee Name')}}</th>
                                                <th> {{__('language.Last Login')}}</th>
                                                <th> {{__('language.Actions')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- noAccessEmployees --}}
                                            @foreach($noAccessEmployees as $key => $employee)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                                <td class="text-nowrap">{{\Carbon\Carbon::parse($employee->last_login)->format("M d,Y h:i A")}}</td>
                                                <td>
                                                    <div class="col-md-6">
                                                        <div class="btn-group">
                                                            <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="top" data-original-title="Without Fade Animation" title="Change Access" data-toggle="tooltip">
                                                                <i data-feather='settings'></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="max-height:240px; overflow:auto;">
                                                                <div class="form-group">
                                                                    @foreach($adminRole as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                        @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                        {{__('language.Full Admin')}}
                                                                    </a>
                                                                    @endforeach
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/noaccess/role/'.$employee->id)}}
                                                                    @else {{url('en/access-levels/addemployee/noaccess/role/'.$employee->id)}} @endif">
                                                                        {{__('language.No Access')}}
                                                                    </a>
                                                                    @if($employeeRoles->isNotEmpty())
                                                                    <div class="dropdown-divider"></div>
                                                                    <small class="pl-1"><b><u>{{__('language.Employee')}}</u></b></small>
                                                                    @foreach($employeeRoles as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                        @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                        {{$role->name}}
                                                                    </a>
                                                                    @endforeach
                                                                    @endif
                                                                    @if($managerRoles->isNotEmpty())
                                                                    <div class="dropdown-divider"></div>
                                                                    <small class="pl-1"><b><u>{{__('language.Manager')}}</u></b></small>
                                                                    @foreach($managerRoles as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                        @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                        {{$role->name}}
                                                                    </a>
                                                                    @endforeach
                                                                    @endif
                                                                    @if($customRoles->isNotEmpty())
                                                                    <div class="dropdown-divider"></div>
                                                                    <small class="pl-1"><b><u>{{__('language.Custom')}}</u></b></small>
                                                                    @foreach($customRoles as $role)
                                                                    <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/access-levels/addemployee/'.$role->id.'/role/'.$employee->id)}}
                                                                            @else {{url('en/access-levels/addemployee/'.$role->id.'/role/'.$admin->id)}} @endif">
                                                                        {{$role->name}}
                                                                    </a>
                                                                    @endforeach
                                                                    @endif
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" class="btn btn-primary btn-lg" employee-id="{{$employee->id}}" data-toggle="modal" onclick="assignMultipleRoles({{$employee->id}},{{$customRoles}},{{$managerRoles}});return false;" href="#">
                                                                        Multiple Access Levels...
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- @else --}}
                                    {{-- <p class="text-center"> {{__('language.No Employee found')}}</p> --}}
                                    {{-- @endif --}}
                                    <!--end: Datatable -->
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- Modal -->
<div class="modal fade " id="multiple-access-levels" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="@if(isset($locale)){{url($locale.'/access-levels/assign-multiple-roles')}}
                @else {{url('en/access-levels/assign-multiple-roles')}} @endif" method="post">
            {{ csrf_field() }}
            <div class="modal-content modal-sm ">
                <div class="modal-header">
                    <h3 class="modal-title">Assign Multple Access Levels</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="multiple-access-levels-body">
                    <h4>Access Levels</h4>
                    <b id="manager-roles-exist">Manager Roles</b>
                    <div id="manager-roles"></div>
                    <b id="custom-roles-exist">Custom Roles</b>
                    <div id="custom-roles"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function assignMultipleRoles(employeeId, customRoles, managerRoles) {

        let managerRolesDiv = $.map(managerRoles, function(value, index) {
            return '<div class="form-check">\n' +
                '    <label class="form-check-label">\n' +
                '        <input type="checkbox" class="form-check-input" name="multiple_access_levels[]" value="' + value.id + '" >\n' +
                '        <b>' + value.name + '</b>\n' +
                '    </label>\n' +
                '</div>'
        });
        let customRoleDiv = $.map(customRoles, function(value, index) {
            return '<div class="form-check">\n' +
                '    <label class="form-check-label">\n' +
                '        <input type="checkbox" class="form-check-input" name="multiple_access_levels[]" value="' + value.id + '">\n' +
                '        <b>' + value.name + '</b>\n' +
                '    </label>\n' +
                '</div>'
        });
        if(managerRolesDiv.length != 0)
        {
            $("#manager-roles-exist").show();
            $('#manager-roles').append(
                managerRolesDiv
            );
        }
        else
        {
            $("#manager-roles-exist").hide();
        }
        if(customRoleDiv.length != 0) {
            $("#custom-roles-exist").show();
            $('#custom-roles').append(
                customRoleDiv,
                '<input type="hidden" value="' + employeeId + '" name="employee">'
            );
        }
        else
        {
            $("#custom-roles-exist").hide();
        }
        $("#multiple-access-levels").modal('show');
    }
    $( document ).ready(function() {
        $('#multiple-access-levels').on('hidden.bs.modal', function (e) {
            alert("hidden");
            $('#manager-roles').empty();
            $('#custom-roles').empty();
        });
    });
</script>

@section('vendor-script')
{{-- vendor files --}}
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

@endsection
@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection
@stop
