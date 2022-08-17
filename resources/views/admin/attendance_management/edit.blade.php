@extends('layouts/contentLayoutMaster')
@section('title','Edit Attendance')
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
                        <h6>Date: {{$employeeAttendance->created_at->format('d-m-Y')}}</h6>
                    </div>
                </div>

                <form class="pt-1" id="edit-attendance" action="@if(isset($locale)) {{route('attendance-management.update', [$locale, $employeeAttendance->id])}} @else {{route('attendance-management.update', ['en', $employeeAttendance->id])}} @endif" method="post">
                    <input type="hidden" name="_method" value="PUT"/>
                    <input type="hidden" name="attendance_id" value="{{$employeeAttendance->id}}"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label" for="time_in">Time In <span class="text-danger">*</span></label><input type="time" class="form-control"  name="time_in" id="time_in" value="{{old('time_in', \Carbon\Carbon::parse($employeeAttendance->time_in)->format('H:i'))}}" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label" for="time_out">Time Out <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="time_out" id="time_out" value="{{old('time_out', \Carbon\Carbon::parse($employeeAttendance->time_out)->format('H:i'))}}" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="time_in_status">Time In Status</label>
                                <select class="form-control" name="time_in_status" id="time_in_status">
                                    <option value="">Select Time In Status</option>
                                    <option value="Late" @if($employeeAttendance->time_in_status == 'Late') selected @endif>Late</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="attendance_status">Attendance Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="attendance_status" id="attendance_status">
                                    <option value="">Select Attendance Status</option>
                                    <option value="Present" @if(strpos($employeeAttendance->attendance_status, 'Present') !== false) selected @endif>Present</option>
                                    <option value="Absent" @if(strpos($employeeAttendance->attendance_status, 'Absent') !== false) selected @endif>Absent</option>
                                    <option value="Holiday" @if(strpos($employeeAttendance->attendance_status, 'Holiday') !== false) selected @endif>Holiday</option>
                                    <option value="Paid Time Off" @if(strpos($employeeAttendance->attendance_status, 'Paid Time') !== false) selected @endif>Paid Time Off</option>
                                    <option value="Leave Without Pay" @if(strpos($employeeAttendance->attendance_status, 'Leave Without') !== false) selected @endif>Leave Without Pay</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="reason_for_leaving">Reason For Leaving</label>
                                <textarea class="form-control" name="reason_for_leaving" id="reason_for_leaving" placeholder="Enter Reason For Leaving">{{ old('reason_for_leaving', $employeeAttendance->reason_for_leaving)}}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row col-12">
                        <button type="button" onclick="validateWorkSchedule();" class="btn btn-primary mr-1">{{__('language.Update')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/attendance-management')}} @else {{url('en/attendance-management')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}
                        </button>
                    </div>
                    <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel1">Attendance Confirmation</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body mt-1">
                                        <h5>Selected attendance is not according to employee's work schedule. Are you sure you want to update employee attendance?</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.No')}}</button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-float waves-light btn-ok">{{__('language.Yes')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </form>
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
    <script src="{{ asset(mix('js/scripts/forms/validations/form-edit-attendance.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script>
        function validateWorkSchedule()
        {
            var schedule_start_time = '{!! $employeeAttendance->employee->employeeWorkSchedule->schedule_start_time !!}';
            var schedule_end_time = '{!! $employeeAttendance->employee->employeeWorkSchedule->schedule_end_time !!}';
            var schedule_flex_time = '{!! $employeeAttendance->employee->employeeWorkSchedule->flex_time_in !!}';
            var time_in = $("#time_in").val();
            var time_out = $("#time_out").val();
            if ($("#edit-attendance").valid()) {
                $.ajax({
                    type: "get",
                    url: "{{ route('attendance-management.validateWorkSchedule', [$locale]) }}",
                    data:{
                        'schedule_start_time' :  schedule_start_time,
                        'schedule_flex_time' :  schedule_flex_time,
                        'schedule_end_time' :  schedule_end_time,
                        'time_in': time_in,
                        'time_out': time_out,
                    },
                    success: function (data) {                        
                        if (data == true) {
                            $("#edit-attendance").submit();
                        } else {
                            $("#alert-modal").modal('show');
                        }
                    }
                });
            }
        }
    </script>
@endsection