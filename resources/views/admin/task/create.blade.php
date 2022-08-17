@extends('layouts/contentLayoutMaster')
@section('title',ucfirst($task_type)." Task")
@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="modal-content">
                @if(strcasecmp($task_type, "onboarding") == 0)
                <form action="@if(isset($locale)){{url($locale.'/onboarding-tasks')}} @else {{url('en/onboarding-tasks')}} @endif" id="task-form" method="post" enctype="multipart/form-data">
                    @else
                        <form action="@if(isset($locale)){{url($locale.'/offboarding-tasks')}} @else {{url('en/offboarding-tasks')}} @endif" id="task-form" method="post" enctype="multipart/form-data">
                        @endif
                    {{ csrf_field() }}
                    <br>
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 form-group">

                            <label class="control-label">{{__('language.Task Name')}}</label><span class="text-danger"> *</span>
                            <input  type="text" id="taskName" name="taskName" placeholder="{{__('language.Enter')}} {{__('language.Task')}} {{__('language.Here')}}" class="form-control">
                        </div>
                        @if($task_type == "onboarding")
                            <input type="hidden" name="taskType" value="0">
                        @else
                            <input type="hidden" name="taskType" value="1">
                        @endif
                        <div class="col-md-6 form-group">
                            <label class="control-label"> {{__('language.Task Category')}}</label>
                            <select class="form-control custom-select"
                                    data-placeholder="Choose a Category" tabindex="1"
                                    name="taskCategory" id="taskCategory">
                                <option value="" selected>{{__('language.Select')}} {{__('language.Task Category')}}</option>
                                @foreach($taskCategory as $category)
                                    <option value="{{$category->id}}">{{$category->task_category_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 form-group">

                            <label class="control-label">{{__('language.Assigned To')}}</label>
                            <select class="form-control custom-select"
                                    data-placeholder="Choose a Selected Employee" name="assignedTo" id="assignedTo"
                                    tabindex="1">
                                <option value="" selected> {{__('language.Assigned To')}}</option>
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->firstname}} {{$employee->lastname}}</option>
                                @endforeach
                            </select>                        </div>


                    </div> {{--end--}}
                    <div class="row pl-2 pr-2">
                        <div class="col-md-12 form-group pl-1 pr-1">
                            <label class="control-label">{{__('language.Description')}}</label>
                            <textarea class="form-control" name="description" id="description" cols="110" rows="3" placeholder="{{__('language.Enter')}} {{__('language.Description')}} {{__('language.Here')}}"></textarea>
                        </div>
                    </div>
                    {{----Add due date ---}}
                    @if($task_type == "onboarding")
                        <div class="row pl-2 pr-2">
                            <div class="col-12 form-group">

                                <label class="control-label">{{__('language.Due Date')}}</label>
                                {{--                                <select class="form-control custom-select"--}}
                                {{--                                        data-placeholder="Choose a Selected Employee" name="assignedTo" id="assignedTo"--}}
                                {{--                                        tabindex="1">--}}
                                {{--                                    <option value="" selected disabled> {{__('language.Assigned To')}}</option>--}}
                                {{--                                    <option value="employee">Employee</option>--}}
                                {{--                                    <option value="manager">Manager</option>--}}
                                {{--                                    @foreach($employees as $employee)--}}
                                {{--                                        <option value="{{$employee->id}}">{{$employee->firstname}} {{$employee->lastname}}</option>--}}
                                {{--                                    @endforeach--}}
                                {{--                                </select>--}}

                                <div class="custom-control custom-radio mt-1">
                                    <input type="radio" id="none-radio" name="dueDate[status]" value="none" class="custom-control-input due-date" checked="">
                                    <label class="custom-control-label" for="none-radio">None</label>
                                </div>
                                <div class=" custom-control custom-radio mt-1">
                                    <input type="radio" id="hire-radio" name="dueDate[status]" value="on_hire_date" class="custom-control-input due-date" >
                                    <label class="custom-control-label" for="hire-radio">On Hire Date</label>
                                </div>

                                <div class=" custom-control custom-radio mt-1 form-horizontal">
                                    <input type="radio" id="specific-radio" name="dueDate[status]" value="specific_date" class="custom-control-input due-date specific-radio-class" >
                                    <label class="custom-control-label d-inline" for="specific-radio">
                                    </label>
                                    <label class=" mt-n50 ">
                                        <div class="input-group ">
                                            <input type="number" class="form-control "
                                                   name="dueDate[days]" id="due-date-days" placeholder="e.g. 15" aria-label="days" value="{{old('dueDate[days]')}}">
                                            <div class="input-group-append " >
                                                <select class="form-control rounded-0 " id="due-date-period" name="dueDate[duration]" >
                                                    <option value="days">days</option>
                                                    <option value="weeks">weeks</option>
                                                    <option value="months">months</option>
                                                </select>
                                                <select class="form-control rounded-0 " id="due-date-time" name="dueDate[period]">
                                                    <option value="after">after</option>
                                                    <option value="before">before</option>
                                                </select>
                                                <span class="input-group-text border-0"><b>hire date</b></span>
                                            </div>

                                        </div>

                                    </label>
                                </div>

                            </div>


                        </div>
                    @else
                        {{----------Offboarding tasks------------}}
                                <div class="row pl-2 pr-2">
                                    <div class="col-12 form-group">

                                        <label class="control-label">{{__('language.Due Date')}}</label>
                                        {{--                                <select class="form-control custom-select"--}}
                                        {{--                                        data-placeholder="Choose a Selected Employee" name="assignedTo" id="assignedTo"--}}
                                        {{--                                        tabindex="1">--}}
                                        {{--                                    <option value="" selected disabled> {{__('language.Assigned To')}}</option>--}}
                                        {{--                                    <option value="employee">Employee</option>--}}
                                        {{--                                    <option value="manager">Manager</option>--}}
                                        {{--                                    @foreach($employees as $employee)--}}
                                        {{--                                        <option value="{{$employee->id}}">{{$employee->firstname}} {{$employee->lastname}}</option>--}}
                                        {{--                                    @endforeach--}}
                                        {{--                                </select>--}}

                                        <div class="custom-control custom-radio mt-1">
                                            <input type="radio" id="none-radio" name="dueDate[status]" value="none" class="custom-control-input due-date" checked="">
                                            <label class="custom-control-label" for="none-radio">None</label>
                                        </div>
                                        <div class=" custom-control custom-radio mt-1">
                                            <input type="radio" id="hire-radio" name="dueDate[status]" value="on_termination_date" class="custom-control-input due-date" >
                                            <label class="custom-control-label" for="hire-radio">On Termination Date</label>
                                        </div>

                                        <div class=" custom-control custom-radio mt-1 form-horizontal">
                                            <input type="radio" id="specific-radio" name="dueDate[status]" value="specific_date" class="custom-control-input due-date specific-radio-class" >
                                            <label class="custom-control-label d-inline" for="specific-radio">
                                            </label>
                                            <label class=" mt-n50 ">
                                                <div class="input-group ">
                                                    <input type="number" class="form-control "
                                                           name="dueDate[days]" id="due-date-days" placeholder="e.g. 15" aria-label="days" value="{{old('dueDate[days]')}}">
                                                    <div class="input-group-append " >
                                                        <select class="form-control rounded-0 " id="due-date-period" name="dueDate[duration]" >
                                                            <option value="days">days</option>
                                                            <option value="weeks">weeks</option>
                                                            <option value="months">months</option>
                                                        </select>
                                                        <select class="form-control rounded-0 " id="due-date-time" name="dueDate[period]">
                                                            <option value="after">after</option>
                                                            <option value="before">before</option>
                                                        </select>
                                                        <span class="input-group-text border-0"><b>termination date</b></span>
                                                    </div>

                                                </div>

                                            </label>
                                        </div>

                                    </div>


                                </div>
                    @endif
                    {{-----End due date----}}
                     {{-- add selected employee--}}
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 form-group">
                            <label class="control-label"> {{__('language.Required for')}}</label>
                            <div class="input-group mb-2">
                                <select class="form-control custom-select"
                                        tabindex="1"
                                        name="requiredFor" id="required-for">
                                    <option value="all">All Employees</option>
                                    <option value="some">Only Some Employees</option>
                                </select>
                                <div class="input-group-append d-none" id="edit-employees">
                                    <span class="input-group-text cursor-pointer" data-toggle="modal" data-target="#employees-filter-sidebar">Edit<i data-feather="chevron-right" class="ml-50"></i> </span>
                                </div>
                            </div>
                        </div>
                    </div>

                            <!-- Add New task Sidebar -->
                            <div class="modal modal-slide-in fade" id="employees-filter-sidebar" aria-hidden="true">
                                <div class="modal-dialog sidebar-lg">
                                    <div class="modal-content p-0">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                        <div class="modal-header mb-1">
                                            <h5 class="modal-title">
                                                <span class="align-middle">{{__('language.Filter')}} {{__('language.Employees')}} {{__('language.By')}}</span>
                                            </h5>
                                        </div>
                                        <div class="modal-body flex-grow-1">
                                            @if($departments->isNotEmpty() ||
                                                $divisions->isNotEmpty()
                                                || $employmentStatus->isNotEmpty()
                                                || $designations->isNotEmpty()
                                                || $locations->isNotEmpty())
                                        {{--                                            <form>--}}
                                        <!-- Collapsible Departments -->
                                            @if($departments->isNotEmpty())
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card form-group">
                                                        <div class="card-header p-0">
                                                            <h4 class="card-title pl-1 pt-1 pb-1">{{__('language.Department')}}</h4>
                                                            <div class="heading-elements">
                                                                <ul class="list-inline pr-1 mb-0">
                                                                    <li>
                                                                        <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="card-content collapse ">
                                                            <div class="card-body">
                                                                @foreach($departments as $department)
                                                                    <div class="">
                                                                        <input type="checkbox" class="employees-filter" name="departments[{{$department->id}}]" id="department{{$department->id}}" />
                                                                        <label class="" for="department{{$department->id}}">{{$department->department_name}}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!--/ Collapsible Departments -->
                                            <!-- Collapsible Divisions -->
                                            @if($divisions->isNotEmpty())
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card form-group">
                                                        <div class="card-header p-0">
                                                            <h4 class="card-title pl-1 pt-1 pb-1">{{__('language.Division')}}</h4>
                                                            <div class="heading-elements">
                                                                <ul class="list-inline pr-1 mb-0">
                                                                    <li>
                                                                        <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="card-content collapse ">
                                                            <div class="card-body">
                                                                @foreach($divisions as $division)
                                                                    <div class="">
                                                                        <input type="checkbox" class="employees-filter" name="divisions[{{$division->id}}]" id="division{{$division->id}}" />
                                                                        <label class="" for="division{{$division->id}}">{{$division->name}}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!--/ Collapsible Divisions--->
                                            <!-- Collapsible Employment status -->
                                            @if($employmentStatus->isNotEmpty())
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card form-group">
                                                        <div class="card-header p-0">
                                                            <h4 class="card-title pl-1 pt-1 pb-1">{{__('language.Employment Status')}}</h4>
                                                            <div class="heading-elements">
                                                                <ul class="list-inline pr-1 mb-0">
                                                                    <li>
                                                                        <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="card-content collapse">
                                                            <div class="card-body">
                                                                @foreach($employmentStatus as $employmentstatus)
                                                                    <div class="">
                                                                        <input type="checkbox" class="employees-filter" name="employmentStatuses[{{$employmentstatus->id}}]" id="employmentStatus{{$employmentstatus->id}}" />
                                                                        <label class="" for="employmentStatus{{$employmentstatus->id}}">{{$employmentstatus->employment_status}}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- / Collapsible Employment status -->
                                            <!-- Collapsible Job Title/Designation -->
                                            @if($designations->isNotEmpty())
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card form-group">
                                                        <div class="card-header p-0">
                                                            <h4 class="card-title pl-1 pt-1 pb-1">{{__('language.Job Title')}}</h4>
                                                            <div class="heading-elements">
                                                                <ul class="list-inline pr-1 mb-0">
                                                                    <li>
                                                                        <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="card-content collapse">
                                                            <div class="card-body">
                                                                @foreach($designations as $designation)
                                                                    <div class="">
                                                                        <input type="checkbox" class="employees-filter" name="designations[{{$designation->id}}]" id="designation{{$designation->id}}" />
                                                                        <label class="" for="designation{{$designation->id}}">{{$designation->designation_name}}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- / Collapsible Job Title/Designation -->
                                            <!-- Collapsible Location -->
                                            @if($locations->isNotEmpty())
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card form-group">
                                                        <div class="card-header p-0">
                                                            <h4 class="card-title pl-1 pt-1 pb-1">{{__('language.Location')}}</h4>
                                                            <div class="heading-elements">
                                                                <ul class="list-inline pr-1 mb-0">
                                                                    <li>
                                                                        <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="card-content collapse">
                                                            <div class="card-body">
                                                                @foreach($locations as $location)
                                                                    <div class="">
                                                                        <input type="checkbox" class="employees-filter" name="locations[{{$location->id}}]" id="location{{$location->id}}" />
                                                                        <label class="" for="location{{$location->id}}">{{$location->name}}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- / Collapsible Location -->
                                            {{--                                                <!--/ Collapsible Employment status -->--}}
                                                @else
                                                    <div class="text-center bg-light-secondary p-2">
                                                        <span class="text-secondary">No data found.</span>
                                                    </div>
                                                @endif
                                            {{--                                            </form>--}}
                                        </div>
                                        @if($departments->isNotEmpty() ||
                                           $divisions->isNotEmpty()
                                           || $employmentStatus->isNotEmpty()
                                           || $designations->isNotEmpty()
                                           || $locations->isNotEmpty())
                                        <div class="fixed-bottom bg-white modal-footer">
                                            <button type="button" class="btn btn-primary mr-1" onclick="addFilters()" >{{__('language.Add')}}</button>
                                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal" >{{__('language.Cancel')}}</button>
                                        </div>
                                        @else
                                            <div class="fixed-bottom bg-white modal-footer">
                                                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal" >{{__('language.Cancel')}}</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- /Add New Customer Sidebar -->
                       {{-- end add selected employee--}}

                    {{-- choose file--}}
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6  pl-1 pr-1">
                            <label class="control-label">{{__('language.Attach')}} {{__('language.File(s)')}}</label>
                            <button type="button" class="btn btn-outline-primary btn-rounded d-block"
                                    data-toggle="modal" data-target="#add-files-sidebar">{{__('language.Add')}} {{__('language.Company')}} {{__('language.Files')}}
                            </button>
                        </div>

                        <!-- Add New task Sidebar -->
                        <div class="modal modal-slide-in fade" id="add-files-sidebar" aria-hidden="true">
                            <div class="modal-dialog sidebar-lg">
                                <div class="modal-content p-0">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title">
                                            <span class="align-middle">{{__('language.Add')}} {{__('language.Files')}}</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                    <!-- Collapsible Departments -->
                                        @if($documents->isNotEmpty())
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">

                                                       <ul class="list-group">
                                                            @foreach($documents as $document)

                                                                    <li class="list-group-item">
                                                                    <input type="checkbox" class="company-files" doc-id="{{$document->id}}" name="documents[{{$document->id}}]" id="file-{{$document->id}}" />
                                                                    <label class="" for="file-{{$document->id}}">{{$document->name}}.{{pathinfo($document->url, PATHINFO_EXTENSION)}}</label>
                                                                    </li>
                                                            @endforeach
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center bg-light-secondary p-2">
                                            <span class="text-secondary">No documents found.</span>
                                            </div>
                                            @endif

                                    <!--/ Collapsible Departments -->
                                    </div>
                               </div>
                            </div>
                        </div>
                        <!-- /Add New Customer Sidebar -->
                    </div>
                        <div class="row selected-files p-1">
                            @foreach($documents as $document)
                                <div class="col-lg-4 col-md-6 col-12 d-none" doc-id="{{$document->id}}" id="file-selected-{{$document->id}}">
                                    <div class=" shadow-none border cursor-pointer">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="mb-0">{{$document->name}}.{{pathinfo($document->url, PATHINFO_EXTENSION)}}</h5>
                                                <ul class="list-inline mb-0 ml-2">
                                                    <li>
                                                        <span class="btn btn-icon p-0 rounded-circle btn-secondary" onclick="removeFile('{{$document->id}}')"><i data-feather="x"></i></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @php
                            if(strcasecmp($task_type, "onboarding") == 0)
                                {
                                    $redirect_to='/onboarding-tasks';
                                }
                                else
                                  {
                            $redirect_to='/offboarding-tasks';
                                 }
                        @endphp
                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Add')}} {{__('language.Task')}} </button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.$redirect_to)}} @else {{url('en'.$redirect_to)}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                    </div>

                </form>
            </div>
            {{--                                                    </div>--}}
            {{--                                                </div>--}}
            <div class="modal" id="taskDocumentDeleted">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Task Document</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            Task document deleted Successfully.
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>

            <!--end: Datatable -->
        </div>
        <!--end::card body-->
    </div>
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}

    <script src="{{ asset(mix('js/scripts/forms/validations/form-tasks-validation.js'))}}"></script>
    <script src="{{ asset(mix('js/scripts/tasks/onboarding.js'))}}"></script>
    <script src="{{ asset(mix('js/scripts/pages/app-user-list.js')) }}"></script>
    <script>
        $(document).ready(function () {


            $('.due-date').click(function () {
                if (!$(this).hasClass('specific-radio-class')) {
                    $('#due-date-days').val('')
                    $('#due-date-period').val('days')
                    $('#due-date-time').val('after')
                }

            });

        });
    </script>
@endsection


