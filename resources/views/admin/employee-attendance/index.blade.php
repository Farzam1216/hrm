@extends('layouts/contentLayoutMaster')
@section('title',$title)
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">

@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">

@endsection

@section('content')
    <!-- Subscribers Chart Card starts -->
    <div class="row">
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card">
                <div class="card-header flex-column align-items-start pb-0">
                    <p class="card-text">Hours Worked Today:</p>
                    <h2 class="font-weight-bolder mt-1">{{$employeeWorkedToday}} hrs</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card">
                <div class="card-header flex-column align-items-start pb-0">
                    <p class="card-text">Hours Worked This Week:</p>
                    <h2 class="font-weight-bolder mt-1">{{$employeeWorkedThisWeek}} hrs</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card">
                <div class="card-header flex-column align-items-start pb-0">
                    <p class="card-text">Hours Worked This Month:</p>
                    <h2 class="font-weight-bolder mt-1">{{$employeeWorkedThisMonth}} hrs</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscribers Chart Card ends -->
    {{--Current Month Attendance Start--}}
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header border-bottom pb-1 pt-1">
                    <div class="head-label">
                        @if(Auth::user()->isAdmin() || $hasApprovalPermission)
                            @if($employeeAttendanceApprovalIsValid)
                                <a href="#" class="btn btn-primary btn-block" data-toggle="modal"
                                   data-target="#new-task-modal"
                                   onclick="UpdateText('Attendance Approval for {{\Carbon\Carbon::now()->format('F')}} {{\Carbon\Carbon::now()->format('Y')}}' )">
                                    {{__('language.Attendance')}} {{__('language.Approval')}}
                                </a>
                            @endif
                        @endif
                    </div>

                    <input type="hidden" id="employee_id" value="{{$employeeID}}">

                    <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex">
                        <div class="row">
                            @if($startTime == null)
                                @if(Auth::user()->isEmployeeOnly())
                                    <span class="text-danger">No Work Schedule Assigned. Please Ask your admin to assign one.</span>
                                @else
                                    <span class="text-danger">No Work Schedule Assigned. Please assign one.</span>
                                @endif
                            @else
                            @if(Auth::user()->isAdmin() || (isset($permissions['attendance']['all']) && $permissions['attendance']['all'][0] == "edit employee_attendance") || (isset($permissions['attendance']) && isset($permissions['attendance'][$employeeID]['employee_attendance']) && $permissions['attendance'][$employeeID]['employee_attendance'] == "edit"))
                                @if($employeeAttendanceToday == null || isset($employeeAttendanceToday->time_out))
                                    <form id="clock-in-form" action="@if(isset($locale)){{url($locale.'/employees/'.$employeeID.'/employee-attendance')}} @else {{url('en/employees/'.$employeeID.'/employee-attendance')}} @endif" method="post">
                                        @csrf
                                        <div class="dropdown">
                                            <div class="mr-1">
                                                @if(Auth::user()->id != $employeeID)
                                                    @if($countAttendanceToday > 0)
                                                        <input type="text" name="clock_type" hidden value="Again_IN">
                                                    @else
                                                        <input type="text" name="clock_type" hidden value="IN">
                                                    @endif
                                                    <button type="button"
                                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i data-feather='plus-circle' class="font-medium-2"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenuLink">
                                                        <a href="#" class="dropdown-item" onclick="$('#clock-in-form').submit();">
                                                            <i data-feather='plus'></i> {{__('language.Add')}} {{__('language.Clock')}} {{__('language.In')}}
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="@if(isset($locale)) {{url($locale.'/attendance/import/create/'.$employeeID)}} @else {{url('en/attendance/import/create/'.$employeeID)}} @endif">
                                                            
                                                        <i data-feather="upload"></i>
                                                            <span class="d-none d-lg-inline d-md-inline d-sm-none"> Import Employees Attendance</span>
                                                        </a>
                                                    </div>
                                                @else
                                                    @if($countAttendanceToday > 0)
                                                        <input type="text" name="clock_type" hidden value="Again_IN">
                                                    @else
                                                        <input type="text" name="clock_type" hidden value="IN">
                                                    @endif
                                                    @if(Auth::user()->isAdmin() || isset($permissions['attendance']['all']) && array_intersect(['manage employees attendance'], $permissions['attendance']['all'] ))
                                                        {{---- @if(Auth::user()->id == $employeeID)
                                                            <button type="submit" class="btn btn-primary btn-block">
                                                                <i data-feather='plus'></i> {{__('language.Add')}} {{__('language.Clock')}} {{__('language.In')}}
                                                            </button>
                                                        @else----}}
                                                        @if($countAttendanceToday > 0)
                                                            <input type="text" name="clock_type" hidden value="Again_IN">
                                                            <input type="hidden" name="reason_for_leaving"  value="no reason">
                                                        @else
                                                            <input type="text" name="clock_type" hidden value="IN">
                                                            <input type="hidden" name="reason_for_leaving"  value="no reason">
                                                        @endif
                                                        <button type="button"
                                                                class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            <i data-feather='plus-circle' class="font-medium-2"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                                aria-labelledby="dropdownMenuLink">
                                                            <a href="#" class="dropdown-item" onclick="$('#clock-in-form').submit();">
                                                                <i data-feather='plus'></i> {{__('language.Add')}} {{__('language.Clock')}} {{__('language.In')}}
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="@if(isset($locale)) {{url($locale.'/attendance/import/create/'.$employeeID)}} @else {{url('en/attendance/import/create/'.$employeeID)}} @endif">
                                                                
                                                            <i data-feather="upload"></i>
                                                                <span class="d-none d-lg-inline d-md-inline d-sm-none"> Import Employees Attendance</span>
                                                            </a>
                                                        </div>
                                                        {{--@endif----}}
                                                    @else
                                                        <button type="submit" class="btn btn-primary btn-block">
                                                            <i data-feather='plus'></i> {{__('language.Add')}} {{__('language.Clock')}} {{__('language.In')}}
                                                        </button>
                                                    @endif 
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <form id="clock-out-form" action="@if(isset($locale)){{url($locale.'/employees/'.$employeeID.'/employee-attendance')}} @else {{url('en/employees/'.$employeeID.'/employee-attendance')}} @endif" method="post">
                                        @csrf
                                        <input type="text" name="clock_type" hidden value="OUT">
                                        <input type="hidden" value="{{$employeeID}}" id="employeeID">
                                        <div class="dropdown">
                                            <div class="mr-1">
                                                    @if(Auth::user()->id != $employeeID)
                                                        <button type="button"
                                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i data-feather='plus-circle' class="font-medium-2"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                            aria-labelledby="dropdownMenuLink">
                                                            <a href="#" class="dropdown-item" @if($employeeClockOutValid) onclick="$('#clock-out-form').submit();"
                                                                @else type="button"
                                                                @endif
                                                                @if(!$employeeClockOutValid) data-toggle="modal"
                                                                data-target="#new-task-modal"
                                                                onclick="UpdateText('Add Clock Out')" @endif>
                                                                <i data-feather='plus'></i> {{__('language.Add')}} {{__('language.Clock')}} {{__('language.Out')}}
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="@if(isset($locale)) {{url($locale.'/attendance/import/create/'.$employeeID)}} @else {{url('en/attendance/import/create/'.$employeeID)}} @endif">
                                                                
                                                            <i data-feather="upload"></i>
                                                                <span class="d-none d-lg-inline d-md-inline d-sm-none"> Import Employees Attendance</span>
                                                            </a>
                                                        </div>
                                                    @else
                                                    <input type="text" name="employee_id" hidden
                                                           value="{{$employeeID}}">
                                                        <button @if($employeeClockOutValid) type="submit"
                                                        @else type="button"
                                                        @endif class="btn btn-primary btn-block"
                                                        @if(!$employeeClockOutValid) data-toggle="modal"
                                                        data-target="#new-task-modal"
                                                        onclick="UpdateText('Add Clock Out')" @endif>
                                                            <i data-feather='plus'></i> {{__('language.Add')}} {{__('language.Clock')}} {{__('language.Out')}}
                                                        </button>
                                                    @endif
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            @endif
                            @endif
                            {{--                            @endif--}}
                        </div>
                    </div>
                </div> <!--end card-header-->
                <div class="card-datatable table-responsive pt-0 p-1">
                    <table class="dt-simple-header table dataTable dtr-column">
                        <thead class="thead-light">
                        <tr>
                            <th>{{__('language.Date')}}</th>
                            <th>{{__('language.Time')}} {{__('language.In')}}</th>
                            <th> {{__('language.Time')}} {{__('language.Out')}}</th>
                            <th> {{__('language.Reason For Leaving')}}</th>
                            <th> {{__('language.Action')}}</th>

                        </tr>
                        </thead>
                        @if($employeeAllTodaysAttendanceToday)
                            <tbody>
                                @foreach($employeeAllTodaysAttendanceToday as $key => $employeeAttendanceToday)
                                <tr>
                                    <td>{{$employeeAttendanceToday->created_at->format('d-m-Y')}}</td>
                                    <td>
                                        @if(isset($employeeAttendanceToday->time_in))
                                            {{$employeeAttendanceToday->time_in}}
                                            @if($employeeAttendanceToday->time_in_status != null)
                                                <div class="avatar bg-danger">
                                                    <div class="badge">Late</div>
                                                </div>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($employeeAttendanceToday->time_out))
                                            {{$employeeAttendanceToday->time_out}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($employeeAttendanceToday->reason_for_leaving))
                                            {{$employeeAttendanceToday->reason_for_leaving}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasApprovalPermission)
                                            <a data-toggle="modal"
                                               data-target="#new-task1-modal"><i data-feather='message-square'></i>
                                            </a>
                                        @endif
                                        <a data-toggle="modal"
                                           data-target="#new-comments-modal{{$employeeAttendanceToday->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-eye">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                    </td>

                                    {{--Show comments model--}}
                                    <div class="modal modal-slide-in sidebar-todo-modal fade"
                                         id="new-comments-modal{{$employeeAttendanceToday->id}}">
                                        <div class="modal-dialog sidebar-lg">
                                            <div class="modal-content p-0 ">
                                                <form id="form-modal-todo" class="todo-modal needs-validation"
                                                      novalidate onsubmit="return false">
                                                    <button
                                                            type="button"
                                                            class="close font-large-1 font-weight-normal py-0"
                                                            data-dismiss="modal"
                                                            aria-label="Close"
                                                    >
                                                        ×
                                                    </button>
                                                    <div class="modal-header align-items-center mb-1">
                                                        <h5 class="modal-title">Approval Comments</h5>

                                                        <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                        </div>
                                                    </div>
                                                    <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                        <div class="action-tags">
                                                            @if(count($employeeAttendanceToday->comments) > 0)
                                                                @foreach($employeeAttendanceToday->comments as $comment)
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label for="exampleFormControlTextarea1">{{$comment->employee->firstname}} {{$comment->employee->lastname}} </label>
                                                                                <textarea disabled
                                                                                          class="form-control"
                                                                                          id="exampleFormControlTextarea1"
                                                                                          rows="3"
                                                                                >{{$comment->comment}}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <h4 class="text-danger">No Comments Found</h4>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{--Add Comment Model--}}
                                    <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task1-modal">
                                        <div class="modal-dialog sidebar-lg">
                                            <div class="modal-content p-0 ">
                                                <form id="form-modal-todo"
                                                      action="@if(isset($locale)){{url($locale.'/employee-attendance/'.$employeeAttendanceToday->id.'/comments')}}
                                                      @else {{url('en/employee-attendance/'.$employeeAttendanceToday->id.'/comments')}} @endif"
                                                      class="todo-modal needs-validation"
                                                      method="post">
                                                    @csrf
                                                    <button
                                                            type="button"
                                                            class="close font-large-1 font-weight-normal py-0"
                                                            data-dismiss="modal"
                                                            aria-label="Close"
                                                    >
                                                        ×
                                                    </button>
                                                    <div class="modal-header align-items-center mb-1">
                                                        <h5 class="modal-title">Add Comment on this
                                                            Attendance Entry</h5>
                                                        <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                        </div>
                                                    </div>
                                                    <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                        <div class="action-tags">

                                                            <div class="form-group">
                                                                <label for="fp-time">Comment</label>
                                                                <textarea type="text" name="comment"
                                                                          class="form-control text-left"
                                                                          placeholder="Comment"></textarea>
                                                            </div>

                                                            <input type="text" name="employee_id" hidden
                                                                   value="{{$employeeID}}">
                                                            <div class=" d-flex flex-sm-row flex-column mt-2 ">
                                                                <button type="submit"
                                                                        class="btn btn-primary mb-1 mb-sm-0  mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Add')}} </button>


                                                                <button type="button"
                                                                        data-dismiss="modal"
                                                                        class="btn btn-inverse">{{__('language.Cancel')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                @endforeach
                            <!-- <tr> {{__('language.No Attendance Found')}}</tr> -->
                        @endif
                            </tbody>
                    </table>
                    {{--                    @endif--}}
                </div> <!--end card-datatable-->
            </div> <!--end card-->
        </div> <!--end col-lg-12-->
    </div> <!--end row-->
    {{--Current Month Attendance Ends--}}

    {{--    Previous Month Attendance Start--}}
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header border-bottom pb-1 pt-1">
                    <div class="head-label">
                        <h3>All Attendance</h3>
                    </div>
                    <br>

                    <div class="dt-action-buttons  dt-buttons flex-wrap d-inline-flex">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="fp-range">Filter</label>
                                <input
                                        type="text"
                                        id="fp-range date-range"
                                        class="form-control flatpickr-range check"
                                        placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                />
                            </div>
                        </div>
                    </div>

                </div> <!--end card-header-->
                <div class="card-datatable table-responsive pt-0 p-1">
                    <table class="table" id="all_attendance_table">
                        <thead class="thead-light">
                        <tr>
                            <th>{{__('language.Date')}}</th>
                            <th>{{__('language.Time')}} {{__('language.In')}}</th>
                            <th> {{__('language.Time')}} {{__('language.Out')}}</th>
                            <th> {{__('language.Reason For Leaving')}}</th>
                            <th> {{__('language.Attendance Status')}}</th>
                            <th> {{__('language.Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($allAttendance)
                                @foreach($allAttendance as $key=> $attendance)
                                    <tr>
                                        @if(isset($attendance->created_at))
                                            <td>{{\Carbon\Carbon::parse($attendance->created_at)->format('d-m-Y')}}</td>
                                        @endif
                                        @if(isset($attendance['created_at']))
                                            <td>{{\Carbon\Carbon::parse($attendance['created_at'])->format('d-m-Y')}}</td>
                                        @endif
                                        <td>
                                            @if($attendance['time_in'])
                                                {{$attendance['time_in']}}
                                                @if($attendance['time_in_status'] != null)
                                                    <div class="avatar bg-danger">
                                                        <div class="badge">Late</div>
                                                    </div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($attendance['time_out']))
                                                {{$attendance['time_out']}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($attendance['reason_for_leaving']))
                                                {{$attendance['reason_for_leaving']}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($attendance['attendance_status']))
                                                {{$attendance['attendance_status']}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance['id'])
                                                <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" href="@if(isset($locale)) {{ route('employees.employee-attendance.show', [$locale, $attendance['employee_id'], \Carbon\Carbon::parse($attendance['created_at'])->format('Y-m-d')]) }} @else {{ route('employees.employee-attendance.show', ['en', $attendance['employee_id'], \Carbon\Carbon::parse($attendance['created_at'])->format('Y-m-d')]) }} @endif" data-toggle="tooltip" data-original-title="View Attendance">
                                                    <i data-feather='eye'></i>
                                                </a>
                                            @endif

                                            <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="modal" data-target="#new-comments-modal{{$attendance['id']}}"><i data-feather='message-square' data-toggle="tooltip" data-original-title="View Comments"></i>
                                            </a>

                                            <a href="@if(isset($locale)) {{ route('employees.employee-attendance.correction-requests.create', [$locale, $employeeID, \Carbon\Carbon::parse($attendance['created_at'])->format('Y-m-d')]) }} @else {{ route('employees.employee-attendance.correction-requests.create', ['en', $employeeID, \Carbon\Carbon::parse($attendance['created_at'])->format('Y-m-d')]) }} @endif" class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="tooltip" data-original-title="Create Correction Request"><i class="fas fa-file-signature"></i></a>
                                        </td>

                                        {{--Show comments model--}}
                                        <div class="modal modal-slide-in sidebar-todo-modal fade"
                                             id="new-comments-modal{{$attendance['id']}}">
                                            <div class="modal-dialog sidebar-lg">
                                                <div class="modal-content p-0 ">
                                                    <form id="form-modal-todo" class="todo-modal needs-validation"
                                                          novalidate onsubmit="return false">
                                                        <button
                                                                type="button"
                                                                class="close font-large-1 font-weight-normal py-0"
                                                                data-dismiss="modal"
                                                                aria-label="Close"
                                                        >
                                                            ×
                                                        </button>
                                                        <div class="modal-header align-items-center mb-1">
                                                            <h5 class="modal-title">Approval Comments</h5>

                                                            <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                        <div class="action-tags">
                                                            @php $count = 0; @endphp
                                                            @foreach($employee as $emp)
                                                                @foreach($emp['employeeAttendance'] as $employeeAttendance)
                                                                    @if($employeeAttendance->id == $attendance['id'])
                                                                        @if(count($employeeAttendance['comments']) > 0)
                                                                            @foreach($employeeAttendance['comments'] as $comment)
                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <label for="exampleFormControlTextarea1">{{$comment->employee->firstname}} {{$comment->employee->lastname}}</label>
                                                                                            <textarea disabled
                                                                                                      class="form-control"
                                                                                                      id="exampleFormControlTextarea1"
                                                                                                      rows="3"
                                                                                            >{{$comment->comment}}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @php $count++; @endphp
                                                                            @endforeach
                                                                        @else
                                                                            <h4 class="text-danger">No Comments Found</h4>
                                                                            @php $count++; @endphp
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endforeach

                                                            @if($count == 0)
                                                                <h4 class="text-danger">No Comments Found</h4>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{--Add Comment Model--}}
                                        <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task1-modal">
                                            <div class="modal-dialog sidebar-lg">
                                                <div class="modal-content p-0 ">
                                                    <form id="form-modal-todo"
                                                          action="@if(isset($locale)){{url($locale.'/employee-attendance/'.$attendance['id'].'/comments')}}
                                                          @else {{url('en/employee-attendance/'.$attendance['id'].'/comments')}} @endif"
                                                          class="todo-modal needs-validation"
                                                          method="post">
                                                        @csrf
                                                        <button
                                                                type="button"
                                                                class="close font-large-1 font-weight-normal py-0"
                                                                data-dismiss="modal"
                                                                aria-label="Close"
                                                        >
                                                            ×
                                                        </button>
                                                        <div class="modal-header align-items-center mb-1">
                                                            <h5 class="modal-title">Add Comment on this
                                                                Attendance Entry</h5>
                                                            <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                            </div>
                                                        </div>
                                                        <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                            <div class="action-tags">

                                                                <div class="form-group">
                                                                    <label for="fp-time">Comment</label>
                                                                    <textarea type="text" name="comment"
                                                                              class="form-control text-left"
                                                                              placeholder="Comment"></textarea>
                                                                </div>

                                                                <input type="text" name="employee_id" hidden
                                                                       value="{{$employeeID}}">
                                                                <div class=" d-flex flex-sm-row flex-column mt-2 ">
                                                                    <button type="submit"
                                                                            class="btn btn-primary mb-1 mb-sm-0  mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Add')}} </button>


                                                                    <button type="button"
                                                                            data-dismiss="modal"
                                                                            class="btn btn-inverse">{{__('language.Cancel')}}</button>
                                                                </div>
                                                            </div>
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
                    @foreach($employee as $emp)
                        @foreach($emp['employeeAttendance'] as $attendance)
                            {{--Show comments model--}}
                            <div class="modal modal-slide-in sidebar-todo-modal fade"
                                 id="new-comments-modal{{$attendance['id']}}">
                                <div class="modal-dialog sidebar-lg">
                                    <div class="modal-content p-0 ">
                                        <form id="form-modal-todo" class="todo-modal needs-validation"
                                              novalidate onsubmit="return false">
                                            <button
                                                    type="button"
                                                    class="close font-large-1 font-weight-normal py-0"
                                                    data-dismiss="modal"
                                                    aria-label="Close"
                                            >
                                                ×
                                            </button>
                                            <div class="modal-header align-items-center mb-1">
                                                <h5 class="modal-title">Approval Comments</h5>

                                                <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                </div>
                                            </div>
                                            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                <div class="action-tags">
                                                    @php $count = 0; @endphp
                                                    @foreach($employee as $emp)
                                                        @foreach($emp['employeeAttendance'] as $employeeAttendance)
                                                            @if($employeeAttendance->id == $attendance['id'])
                                                                @if(count($employeeAttendance['comments']) > 0)
                                                                    @foreach($employeeAttendance['comments'] as $comment)
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="exampleFormControlTextarea1">{{$comment->employee->firstname}} {{$comment->employee->lastname}}</label>
                                                                                    <textarea disabled
                                                                                              class="form-control"
                                                                                              id="exampleFormControlTextarea1"
                                                                                              rows="3"
                                                                                    >{{$comment->comment}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @php $count++; @endphp
                                                                    @endforeach
                                                                @else
                                                                    <h4 class="text-danger">No Comments Found</h4>
                                                                    @php $count++; @endphp
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endforeach

                                                    @if($count == 0)
                                                        <h4 class="text-danger">No Comments Found</h4>
                                                    @endif
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{--Add Comment Model--}}
                            <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task1-modal">
                                <div class="modal-dialog sidebar-lg">
                                    <div class="modal-content p-0 ">
                                        <form id="form-modal-todo"
                                              action="@if(isset($locale)){{url($locale.'/employee-attendance/'.$attendance['id'].'/comments')}}
                                              @else {{url('en/employee-attendance/'.$attendance['id'].'/comments')}} @endif"
                                              class="todo-modal needs-validation"
                                              method="post">
                                            @csrf
                                            <button
                                                    type="button"
                                                    class="close font-large-1 font-weight-normal py-0"
                                                    data-dismiss="modal"
                                                    aria-label="Close"
                                            >
                                                ×
                                            </button>
                                            <div class="modal-header align-items-center mb-1">
                                                <h5 class="modal-title">Add Comment on this
                                                    Attendance Entry</h5>
                                                <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                                                </div>
                                            </div>
                                            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                <div class="action-tags">

                                                    <div class="form-group">
                                                        <label for="fp-time">Comment</label>
                                                        <textarea type="text" name="comment"
                                                                  class="form-control text-left"
                                                                  placeholder="Comment"></textarea>
                                                    </div>

                                                    <input type="text" name="employee_id" hidden
                                                           value="{{$employeeID}}">
                                                    <div class=" d-flex flex-sm-row flex-column mt-2 ">
                                                        <button type="submit"
                                                                class="btn btn-primary mb-1 mb-sm-0  mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Add')}} </button>


                                                        <button type="button"
                                                                data-dismiss="modal"
                                                                class="btn btn-inverse">{{__('language.Cancel')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div> <!--end card-datatable-->
            </div> <!--end card-->
        </div> <!--end col-lg-12-->
    </div> <!--end row-->



    <!-- Right Sidebar starts -->
    <div class="modal modal-slide-in sidebar-todo-modal fade" id="new-task-modal">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0 " id="sidebar-content">

            </div>
        </div>
    </div>
    <!-- Right Sidebar ends -->

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
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/attendance/index.js')) }}"></script>
@endsection
@stop
