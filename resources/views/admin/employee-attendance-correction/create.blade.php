@extends('layouts/contentLayoutMaster')
@section('title','Create Correction Request')
@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom">
                    <div class="head-label"> 
                        <h6>Date: {{$date->format('d-m-Y')}}</h6>
                    </div>
                </div>
                <div class="hidden pt-2" id="no_attendance_error"><span class="error">Please add attendance to create correction request</span></div>

                <form class="pt-1" id="request-attendance-correction" action="@if(isset($locale)) {{route('employees.employee-attendance.correction-requests.store', [$locale, $employee->id, $date->format('d-m-Y')])}} @else {{route('employees.employee-attendance.correction-requests.store', ['en', $employee->id, $date->format('d-m-Y')])}} @endif" method="post">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{$employee->id}}"/>
                    <input type="hidden" name="date" value="{{$date->format('Y-m-d')}}"/>
                    @php $count = 0; @endphp
                    @foreach($employee['employeeAttendance'] as $employeeAttendance)
                        @if($employeeAttendance->created_at->format('Y-m-d') == $date->format('Y-m-d'))
                            @php ++$count; @endphp
                            <input type="hidden" name="attendance_id[]" value="{{$employeeAttendance->id}}"/>
                            <div id="remove_attendance_div"></div>

                            <div class="row">
                                <a href="#" class="col-12 text-right remove_field" attendance_id="{{$employeeAttendance->id}}">Remove</a>

                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="time_in">Time In</label>
                                        <input type="time" class="form-control time_in" name="time_in[]" id="time_in{{$count}}" number="{{$count}}" value="{{old('time_in', \Carbon\Carbon::parse($employeeAttendance->time_in)->format('H:i'))}}" onchange="change(this);">
                                        <span id="error_time_in{{$count}}" class="error hidden">Time in should be smaller than time out.</span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="time_out">Time Out</label>
                                        <input type="time" class="form-control time_out" name="time_out[]" id="time_out{{$count}}" number="{{$count}}" value="{{old('time_out', \Carbon\Carbon::parse($employeeAttendance->time_out)->format('H:i'))}}" onchange="change(this);">
                                        <span id="error_time_out{{$count}}" class="error hidden">Time out should be greater than time in.</span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="time_in_status">Time In Status</label>
                                        <select class="form-control time_in_status" name="time_in_status[]" id="time_in_status{{$count}}" number="{{$count}}">
                                            <option value="none" @if($employeeAttendance->time_in_status == 'none') selected @endif>None</option>
                                            <option value="Late" @if($employeeAttendance->time_in_status == 'Late') selected @endif>Late</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="attendance_status">Attendance Status</label>
                                        <select class="form-control attendance_status" name="attendance_status[]" id="attendance_status{{$count}}" number="{{$count}}">
                                            <option value="">Select Attendance Status</option>
                                            @if($employeeAttendance->time_in != ' ' || $employeeAttendance->time_out != ' ' || $employeeAttendance->time_in != '' || $employeeAttendance->time_out != '')
                                                <option value="Present" id="present{{$count}}" @if(strpos($employeeAttendance->attendance_status, 'Present') !== false) selected @endif>Present</option>
                                            @endif

                                            @if($employeeAttendance->time_in == ' ' || $employeeAttendance->time_out == ' ' || $employeeAttendance->time_in == '' || $employeeAttendance->time_out == '')
                                                <option value="Absent" id="absent{{$count}}" @if(strpos($employeeAttendance->attendance_status, 'Absent') !== false) selected @endif>Absent</option>
                                                <option value="Holiday" id="holiday{{$count}}" @if(strpos($employeeAttendance->attendance_status, 'Holiday') !== false) selected @endif>Holiday</option>
                                                <option value="Paid Time Off" id="paid_time_off{{$count}}" @if(strpos($employeeAttendance->attendance_status, 'Paid Time') !== false) selected @endif>Paid Time Off</option>
                                                <option value="Leave Without Pay" id="leave_without_pay{{$count}}" @if(strpos($employeeAttendance->attendance_status, 'Leave Without') !== false) selected @endif>Leave Without Pay</option>
                                            @endif
                                        </select>
                                        <span id="error_attendance_status{{$count}}" class="error hidden">Attendance status is required</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="reason_for_leaving">Reason For Leaving</label>
                                        <input type="text" class="form-control reason_for_leaving" name="reason_for_leaving[]" id="reason_for_leaving{{$count}}" placeholder="Enter Reason For Leaving" value="{{ old('reason_for_leaving', $employeeAttendance->reason_for_leaving)}}">
                                    </div>
                                </div>

                                <div class="col-12 pb-1"><div class="border-bottom"></div></div>
                            </div>
                        @endif
                    @endforeach
                    <input type="hidden" name="total_entries" id="total_entries" value="{{$count}}"/>

                    <div class="input_fields_wrap text-right"></div>

                    <button class="btn btn-sm btn-primary add_field_button">Add More Attendance</button>

                    <hr>

                    <div class="row col-12">
                        <button type="button" onclick="validate();" class="btn btn-primary mr-1">{{__('language.Create')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{route('employees.employee-attendance.index', [$locale, $employee->id])}} @else {{route('employees.employee-attendance.index', ['en', $employee->id])}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}
                        </button>
                    </div>
                </form>

                <input type="hidden" name="dummy_ids" id="dummy_ids" value="{{$count}}"/>
            </div>
        </div>
    </div>
</div>
@stop
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset('js/scripts/form-correction-request-create-script.js') }}"></script>
@endsection