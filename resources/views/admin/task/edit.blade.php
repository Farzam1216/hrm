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
                    <form action="@if(isset($locale)){{url($locale.'/onboarding-tasks/'.$task->id)}} @else {{url('en/onboarding-tasks/'.$task->id)}} @endif"
                          id="task-form" method="post" enctype="multipart/form-data">
                        @else
                            <form action="@if(isset($locale)){{url($locale.'/offboarding-tasks/'.$task->id)}} @else {{url('en/offboarding-tasks/'.$task->id)}} @endif"
                                  id="task-form" method="post" enctype="multipart/form-data">
                                @endif
                                <input name="_method" type="hidden" value="PUT">
                                {{csrf_field()}}
                                <br>
                                <div class="row pl-2 pr-2"> {{--task name and task type--}}
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">{{__('language.Task Name')}}</label><span
                                                class="text-danger"> *</span>
                                        <input type="text" id="taskName" name="taskName"
                                               value="{{old('taskname', $task->name)}}" class="form-control">
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
                                                name="taskCategory">
                                            <option value="">{{__('language.Select')}} {{__('language.Task Category')}}</option>
                                            @foreach($taskCategory as $category)
                                                <option value="{{$category->id}}"
                                                        @if(isset($task->category) && $task->category == $category->id)  selected @endif>{{$category->task_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>{{--end task name and task type--}}

                                <div class="row pl-2 pr-2"> {{--assign-to and task-category--}}
                                    <div class="col-md-6 form-group">

                                        <label class="control-label">{{__('language.Assigned To')}}</label>
                                        <select class="form-control custom-select"
                                                data-placeholder="Choose a Selected Employee" name="assignedTo"
                                                id="assignedTo"
                                                tabindex="1">
                                            <option value="" selected> {{__('language.Assigned To')}}</option>
                                            <option value="employee"
                                                    @if($task->assigned_to == "employee") selected @endif>Employee
                                            </option>
                                            <option value="manager"
                                                    @if($task->assigned_to == "manager") selected @endif>Manager
                                            </option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->id}}"
                                                        @if($task->assigned_to == $employee->id)  selected @endif>{{$employee->firstname}} {{$employee->lastname}}</option>
                                            @endforeach
                                        </select></div>


                                </div> {{--end assign-to and task-category--}}
                                <div class="row pl-2 pr-2">{{--description--}}
                                    <div class="col-md-12 form-group pl-1 pr-1">
                                        <label class="control-label">{{__('language.Description')}}</label>
                                        <textarea class="form-control" name="description" id="asset_description"
                                                  cols="110" rows="3">{{$task->description}}</textarea>
                                    </div>
                                </div> {{--end description--}}
                                {{----Edit due date ---}}
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
                                                <input type="radio" id="none-radio" name="dueDate[status]" value="none"
                                                       class="custom-control-input due-date"
                                                       @if($task->due_date == "none") checked="" @endif>
                                                <label class="custom-control-label" for="none-radio">None</label>
                                            </div>
                                            <div class=" custom-control custom-radio mt-1">
                                                <input type="radio" id="hire-radio" name="dueDate[status]"
                                                       value="on_hire_date" class="custom-control-input due-date"
                                                       @if($task->due_date == "on_hire_date") checked="" @endif>
                                                <label class="custom-control-label" for="hire-radio">On Hire
                                                    Date</label>
                                            </div>

                                            <div class=" custom-control custom-radio mt-1 form-horizontal">
                                                <input type="radio" id="specific-radio" name="dueDate[status]"
                                                       value="specific_date"
                                                       class="custom-control-input due-date specific-radio-class"
                                                       @if($task->due_date !== "on_hire_date" && $task->due_date !== "none") checked="" @endif >
                                                <label class="custom-control-label d-inline" for="specific-radio">
                                                </label>
                                                @php
                                                    if($task->due_date !== "on_hire_date" && $task->due_date !== "none")
        {
            //get duration
                                                        preg_match("/[A-Za-z]+/", $task->due_date, $old_duration);
                                                        //remove duration and leave number only
                                                        $old_days=preg_replace("/[a-zA-Z]+/", "", $task->due_date);

        }
        else
            {
                $old_duration=null;
                 $old_days=null;
            }
                                                @endphp
                                                <label class=" mt-n50 ">
                                                    <div class="input-group ">
                                                        <input type="number" class="form-control "
                                                               name="dueDate[days]" id="due-date-days"
                                                               placeholder="e.g. 15" aria-label="days"
                                                               @if(isset($old_days)) value="{{old('dueDate[days]', $old_days)}}"
                                                               @else value="{{old('dueDate[days]')}}" @endif>
                                                        <div class="input-group-append ">
                                                            <select class="form-control rounded-0 " id="due-date-period"
                                                                    name="dueDate[duration]">
                                                                <option value="days"
                                                                        @if(isset($old_duration[0]) && $old_duration[0] == "days") selected @endif>
                                                                    days
                                                                </option>
                                                                <option value="weeks"
                                                                        @if(isset($old_duration[0]) && $old_duration[0] == "weeks") selected @endif>
                                                                    weeks
                                                                </option>
                                                                <option value="months"
                                                                        @if(isset($old_duration[0]) && $old_duration[0] == "months") selected @endif>
                                                                    months
                                                                </option>
                                                            </select>
                                                            <select class="form-control rounded-0 " id="due-date-time"
                                                                    name="dueDate[period]">
                                                                <option value="after"
                                                                        @if($task->period == "after") selected @endif>
                                                                    after
                                                                </option>
                                                                <option value="before"
                                                                        @if($task->period == "before") selected @endif>
                                                                    before
                                                                </option>
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
                                                <input type="radio" id="none-radio" name="dueDate[status]" value="none"
                                                       class="custom-control-input due-date"
                                                       @if($task->due_date == "none") checked="" @endif>
                                                <label class="custom-control-label" for="none-radio">None</label>
                                            </div>
                                            <div class=" custom-control custom-radio mt-1">
                                                <input type="radio" id="hire-radio" name="dueDate[status]"
                                                       value="on_termination_date" class="custom-control-input due-date"
                                                       @if($task->due_date == "on_termination_date") checked="" @endif>
                                                <label class="custom-control-label" for="hire-radio">On Termination
                                                    Date</label>
                                            </div>

                                            <div class=" custom-control custom-radio mt-1 form-horizontal">
                                                <input type="radio" id="specific-radio" name="dueDate[status]"
                                                       value="specific_date"
                                                       class="custom-control-input due-date specific-radio-class"
                                                       @if($task->due_date !== "on_termination_date" && $task->due_date !== "none") checked="" @endif >
                                                <label class="custom-control-label d-inline" for="specific-radio">
                                                </label>
                                                @php
                                                    if($task->due_date !== "on_hire_date" && $task->due_date !== "none")
        {
            //get duration
                                                        preg_match("/[A-Za-z]+/", $task->due_date, $old_duration);
                                                        //remove duration and leave number only
                                                        $old_days=preg_replace("/[a-zA-Z]+/", "", $task->due_date);

        }
        else
            {
                $old_duration=null;
                 $old_days=null;
            }
                                                @endphp
                                                <label class=" mt-n50 ">
                                                    <div class="input-group ">
                                                        <input type="number" class="form-control "
                                                               name="dueDate[days]" id="due-date-days"
                                                               placeholder="e.g. 15" aria-label="days"
                                                               @if(isset($old_days)) value="{{old('dueDate[days]', $old_days)}}"
                                                               @else value="{{old('dueDate[days]')}}" @endif>
                                                        <div class="input-group-append ">
                                                            <select class="form-control rounded-0 " id="due-date-period"
                                                                    name="dueDate[duration]">
                                                                <option value="days"
                                                                        @if(isset($old_duration[0]) && $old_duration[0] == "days") selected @endif>
                                                                    days
                                                                </option>
                                                                <option value="weeks"
                                                                        @if(isset($old_duration[0]) && $old_duration[0] == "weeks") selected @endif>
                                                                    weeks
                                                                </option>
                                                                <option value="months"
                                                                        @if(isset($old_duration[0]) && $old_duration[0] == "months") selected @endif>
                                                                    months
                                                                </option>
                                                            </select>
                                                            <select class="form-control rounded-0 " id="due-date-time"
                                                                    name="dueDate[period]">
                                                                <option value="after"
                                                                        @if($task->period == "after") selected @endif>
                                                                    after
                                                                </option>
                                                                <option value="before"
                                                                        @if($task->period == "before") selected @endif>
                                                                    before
                                                                </option>
                                                            </select>
                                                            <span class="input-group-text border-0"><b>termination date</b></span>
                                                        </div>

                                                    </div>

                                                </label>
                                            </div>

                                        </div>


                                    </div>
                                @endif

                                {{-- add selected employee--}}
                                <div class="row pl-2 pr-2">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label"> {{__('language.Required for')}}</label>
                                        <div class="input-group mb-2">
                                            <select class="form-control custom-select"
                                                    tabindex="1"
                                                    name="requiredFor" id="required-for">
                                                <option value="all"
                                                        @if($task->assigned_for_all_employees) selected @endif>All
                                                    Employees
                                                </option>
                                                <option value="some"
                                                        @if(!$task->assigned_for_all_employees) selected @endif>Only
                                                    Some Employees
                                                </option>
                                            </select>
                                            <div class="input-group-append @if($task->assigned_for_all_employees) d-none @endif"
                                                 id="edit-employees">
                                                <span class="input-group-text cursor-pointer" data-toggle="modal"
                                                      data-target="#employees-filter-sidebar">Edit<i
                                                            data-feather="chevron-right" class="ml-50"></i> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add New task Sidebar -->
                                <div class="modal modal-slide-in fade" id="employees-filter-sidebar" aria-hidden="true"
                                     `>
                                    <div class="modal-dialog sidebar-lg">
                                        <div class="modal-content p-0">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                ×
                                            </button>
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
                                                                                <a data-action="collapse"
                                                                                   @if($taskDepartments->isNotEmpty()) class="rotate" @endif><i
                                                                                            data-feather="chevron-down"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="card-content collapse @if($taskDepartments->isNotEmpty()) show @endif">
                                                                    <div class="card-body">
                                                                        @foreach($departments as $department)
                                                                            <div class="">
                                                                                <input type="checkbox"
                                                                                       class="employees-filter"
                                                                                       name="departments[{{$department->id}}]"
                                                                                       id="department{{$department->id}}"
                                                                                       @if( $taskDepartments->isNotEmpty() && $taskDepartments->where('filter_id', $department->id)->first()) checked @endif/>
                                                                                <label class=""
                                                                                       for="department{{$department->id}}">{{$department->department_name}}</label>
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
                                                                                <a data-action="collapse"
                                                                                   @if($taskDepartments->isNotEmpty()) class="rotate" @endif><i
                                                                                            data-feather="chevron-down"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="card-content collapse  @if( $taskDivisions->isNotEmpty()) show @endif">
                                                                    <div class="card-body">
                                                                        @foreach($divisions as $division)
                                                                            <div class="">
                                                                                <input type="checkbox"
                                                                                       class="employees-filter"
                                                                                       name="divisions[{{$division->id}}]"
                                                                                       id="division{{$division->id}}"
                                                                                       @if($taskDivisions->isNotEmpty() && $taskDivisions->where('filter_id', $division->id)->first()) checked @endif/>
                                                                                <label class=""
                                                                                       for="division{{$division->id}}">{{$division->name}}</label>
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
                                                                                <a data-action="collapse"
                                                                                   @if($taskEmploymentStatus->isNotEmpty()) class="rotate" @endif><i
                                                                                            data-feather="chevron-down"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="card-content collapse  @if( $taskEmploymentStatus->isNotEmpty()) show @endif">
                                                                    <div class="card-body">
                                                                        @foreach($employmentStatus as $employmentstatus)
                                                                            <div class="">
                                                                                <input type="checkbox"
                                                                                       class="employees-filter"
                                                                                       name="employmentStatuses[{{$employmentstatus->id}}]"
                                                                                       id="employmentStatus{{$employmentstatus->id}}"
                                                                                       @if( $taskEmploymentStatus->isNotEmpty() && $taskEmploymentStatus->where('filter_id', $employmentstatus->id)->first()) checked @endif/>
                                                                                <label class=""
                                                                                       for="employmentStatus{{$employmentstatus->id}}">{{$employmentstatus->employment_status}}</label>
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
                                                                                <a data-action="collapse"
                                                                                   @if($taskDesignations->isNotEmpty()) class="rotate" @endif><i
                                                                                            data-feather="chevron-down"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="card-content collapse  @if($taskDesignations->isNotEmpty()) show @endif">
                                                                    <div class="card-body">
                                                                        @foreach($designations as $designation)
                                                                            <div class="">
                                                                                <input type="checkbox"
                                                                                       class="employees-filter"
                                                                                       name="designations[{{$designation->id}}]"
                                                                                       id="designation{{$designation->id}}"
                                                                                       @if( $taskDesignations->isNotEmpty() && $taskDesignations->where('filter_id', $designation->id)->first()) checked @endif/>
                                                                                <label class=""
                                                                                       for="designation{{$designation->id}}">{{$designation->designation_name}}</label>
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
                                                                                <a data-action="collapse"
                                                                                   @if($taskLocations->isNotEmpty()) class="rotate" @endif><i
                                                                                            data-feather="chevron-down"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="card-content collapse  @if($taskLocations->isNotEmpty()) show @endif
                                                                        ">
                                                                    <div class="card-body">
                                                                        @foreach($locations as $location)
                                                                            <div class="">
                                                                                <input type="checkbox"
                                                                                       class="employees-filter"
                                                                                       name="locations[{{$location->id}}]"
                                                                                       id="location{{$location->id}}"
                                                                                       @if($taskLocations->isNotEmpty() && $taskLocations->where('filter_id', $location->id)->first()) checked @endif/>
                                                                                <label class=""
                                                                                       for="location{{$location->id}}">{{$location->name}}</label>
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
                                                <button type="button" class="btn btn-primary mr-1"
                                                        onclick="addFilters()">{{__('language.Add')}}</button>
                                                <button type="reset" class="btn btn-outline-secondary"
                                                        data-dismiss="modal">{{__('language.Cancel')}}</button>
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
                                                data-toggle="modal"
                                                data-target="#add-files-sidebar">{{__('language.Add')}} {{__('language.Company')}} {{__('language.Files')}}
                                        </button>
                                    </div>

                                    <!-- Add New task Sidebar -->
                                    <div class="modal modal-slide-in fade" id="add-files-sidebar" aria-hidden="true">
                                        <div class="modal-dialog sidebar-lg">
                                            <div class="modal-content p-0">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">×
                                                </button>
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
                                                                                <input type="checkbox"
                                                                                       class="company-files"
                                                                                       doc-id="{{$document->id}}"
                                                                                       name="documents[{{$document->id}}]"
                                                                                       id="file-{{$document->id}}"
                                                                                       @if($taskDocuments->isNotEmpty() && $taskDocuments->where('document_id', $document->id)->first()) checked @endif/>
                                                                                <label class=""
                                                                                       for="file-{{$document->id}}">{{$document->name}}
                                                                                    .{{pathinfo($document->url, PATHINFO_EXTENSION)}}</label>
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
                                        <div class="col-lg-4 col-md-6 col-12
                                                 @if(!($taskDocuments->isNotEmpty() && $taskDocuments->where('document_id', $document->id)->first()))
                                                d-none
@endif" doc-id="{{$document->id}}" id="file-selected-{{$document->id}}">
                                            <div class=" shadow-none border cursor-pointer">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="mb-0">{{$document->name}}
                                                            .{{pathinfo($document->url, PATHINFO_EXTENSION)}}</h5>
                                                        <ul class="list-inline mb-0 ml-2">
                                                            <li>
                                                                <span class="btn btn-icon p-0 rounded-circle btn-secondary"
                                                                      onclick="removeFile('{{$document->id}}')"><i
                                                                            data-feather="x"></i></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <br>
                                <div class="row pl-2 pr-2">{{--description--}}
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="apply_to_all"
                                                   id="applyToAll" value="true"/>
                                            <label class="custom-control-label" for="applyToAll">Update existing
                                                employee tasklist items.</label>
                                        </div>
                                    </div>
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
                                    <button type="submit"
                                            class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Update')}} {{__('language.Task')}} </button>
                                    <button type="button"
                                            onclick="window.location.href='@if(isset($locale)){{url($locale.$redirect_to)}} @else {{url('en'.$redirect_to)}} @endif'"
                                            class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                                </div>

                            </form>
            </div>
            {{--                                                    </div>--}}
            {{--                                                </div>--}}
            </tr>
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

            </tbody>
            </table>
            <!--end: Datatable -->
        </div>
        <!--end::card body-->
    </div>
    </div>
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

@endsection


<script>


    {{--$(document).ready(function () {--}}
    {{--    $('input[type="radio"]').click(function () {--}}
    {{--        if ($(this).attr('id') === 'watch-me') {--}}
    {{--            $('#show-me').show();--}}
    {{--        } else {--}}
    {{--            $('#show-me').hide();--}}
    {{--        }--}}
    {{--    });--}}

    {{--    var count = 0;--}}

    {{--    --}}{{--document.getElementById('textarea').value = '{{$task->task_description}}';--}}

    {{--    $('#add').click(function(e){ //click event on add more fields button having class add_more_button--}}
    {{--        e.preventDefault();--}}
    {{--        $('#optionaldocument').append('<div class="row"><div class="col-md-4"><div class="custom mb-2 form-group form-inline"><div class="custom-file"><input type="file" accept=".pdf,.doc" class="custom-file-input"  name="optionalDocument['+ count + '][document]"/><label class="custom-file-label" for="customFile">Choose file</label><button class="remove_field btn btn-danger btn-sm ml-2 mt-1"><i data-feather=\'trash-2\'></i></button></div></div></div></div>'); //add input field--}}
    {{--        //$('#optionaldocument').append('<div class="custom mb-2"><div class="custom-switch-off-danger  "><label for="form-control"> Custom File</label></div><input type="file" accept=".pdf,.doc" class="form-control-file"  name="optionalDocument['+ count + '][document]"/><button class="remove_field btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></button></div>'); //add input field--}}

    {{--        count++;--}}
    {{--    });--}}
    {{--    $('#optionaldocument').on("click", ".remove_field", function (e) { //user click on remove text links--}}
    {{--        e.preventDefault();--}}

    {{--        $(this).parents('.custom').remove();--}}
    {{--    })--}}

    {{--    $('.remove_field').on('click',function(){--}}
    {{--        var task_id = $(this).attr('id');--}}
    {{--        $.ajax({--}}
    {{--            type: 'get',--}}
    {{--            url: '/en/delete/task_template/document',--}}
    {{--            data: {--}}
    {{--                'id': task_id--}}
    {{--            },--}}
    {{--            success: function (taskDocumentId) {--}}
    {{--                var id = taskDocumentId;--}}
    {{--                $('#'+id).parent().remove();--}}
    {{--                $('#taskDocumentDeleted').modal('toggle');--}}
    {{--            }--}}
    {{--        });--}}
    {{--    });--}}
    {{--});--}}

</script>


@stop