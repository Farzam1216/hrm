@extends('layouts/contentLayoutMaster')
@section('title','Edit Correction Request')
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
                    @php $date = \Carbon\Carbon::parse($correctionRequest->date); @endphp
                    <div class="head-label"> 
                        <h6>Date: {{\Carbon\Carbon::parse($correctionRequest->date)->format('d-m-Y')}}</h6>
                    </div>
                </div>
                <div class="hidden pt-2" id="no_attendance_error"><span class="error">Please add attendance to create correction request</span></div>

                <form class="pt-1" id="request-attendance-correction" action="@if(isset($locale)) {{route('correction-requests.update', [$locale, $correctionRequest->id])}} @else {{route('correction-requests.update', ['en', $correctionRequest->id])}} @endif" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    @csrf
                    @php 
                        $count = 0;
                        $time_ins = explode(',',$correctionRequest->time_in);
                        $time_outs = explode(',',$correctionRequest->time_out);
                        $time_in_statuses = explode(',',$correctionRequest->time_in_status);
                        $attendance_statuses = explode(',',$correctionRequest->attendance_status);
                        $reason_for_leavings = explode(',',$correctionRequest->reason_for_leaving);
                    @endphp

                    <div class="input_fields_wrap text-right">
                        @for($index = 0; $index < $correctionRequest->total_entries; $index++)
                            @php ++$count; @endphp
                            <div id="remove_attendance_div"></div>

                            <div class="row">
                                <a href="#" class="col-12 text-right remove_field" attendance_id="">Remove</a>

                                <div class="col-md-3 col-6">
                                    <div class="form-group text-left">
                                        <label class="form-label" for="time_in">Time In</label>
                                        <input type="time" class="form-control time_in" name="time_in[]" id="time_in{{$count}}" number="{{$count}}" value="{{old('time_in', \Carbon\Carbon::parse($time_ins[$index])->format('H:i'))}}" onchange="change(this);">
                                        <span id="error_time_in{{$count}}" class="error hidden"></span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="form-group text-left">
                                        <label class="form-label" for="time_out">Time Out</label>
                                        <input type="time" class="form-control time_out" name="time_out[]" id="time_out{{$count}}" number="{{$count}}" value="{{old('time_out', \Carbon\Carbon::parse($time_outs[$index])->format('H:i'))}}" onchange="change(this);">
                                        <span id="error_time_out{{$count}}" class="error hidden"></span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <div class="form-group text-left">
                                        <label for="time_in_status">Time In Status</label>
                                        <select class="form-control time_in_status" name="time_in_status[]" id="time_in_status{{$count}}" number="{{$count}}">
                                            <option value="none" @if($time_in_statuses[$index] == 'none') selected @endif>None</option>
                                            <option value="Late" @if($time_in_statuses[$index] == 'Late') selected @endif>Late</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-6">
                                    <div class="form-group text-left">
                                        <label for="attendance_status">Attendance Status</label>
                                        <select class="form-control attendance_status" name="attendance_status[]" id="attendance_status{{$count}}" number="{{$count}}">
                                            @if(old('time_in', \Carbon\Carbon::parse($time_ins[$index])->format('H:i')) != ' ' || old('time_in', \Carbon\Carbon::parse($time_ins[$index])->format('H:i')) != '')
                                                <option value="">Select Attendance Status</option>
                                                <option value="Present" id="present{{$count}}" @if(strpos($attendance_statuses[$index], 'Present') !== false) selected @endif>Present</option>
                                            @endif

                                            @if(old('time_in', \Carbon\Carbon::parse($time_ins[$index])->format('H:i')) == ' ' || old('time_in', \Carbon\Carbon::parse($time_ins[$index])->format('H:i')) == '')
                                                <option value="Absent" id="absent{{$count}}" @if(strpos($attendance_statuses[$index], 'Absent') !== false) selected @endif>Absent</option>
                                                <option value="Holiday" id="holiday{{$count}}" @if(strpos($attendance_statuses[$index], 'Holiday') !== false) selected @endif>Holiday</option>
                                                <option value="Paid Time Off" id="paid_time_off{{$count}}" @if(strpos($attendance_statuses[$index], 'Paid Time') !== false) selected @endif>Paid Time Off</option>
                                                <option value="Leave Without Pay" id="leave_without_pay{{$count}}" @if(strpos($attendance_statuses[$index], 'Leave Without') !== false) selected @endif>Leave Without Pay</option>
                                            @endif
                                        </select>
                                        <span id="error_attendance_status{{$count}}" class="error hidden">Attendance status is required</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group text-left">
                                        <label for="reason_for_leaving">Reason For Leaving</label>
                                        <input type="text" class="form-control reason_for_leaving" name="reason_for_leaving[]" id="reason_for_leaving{{$count}}" number="{{$count}}" placeholder="Enter Reason For Leaving" @if(old('reason_for_leaving', $reason_for_leavings[$index]) != ' ') value="{{ old('reason_for_leaving', $reason_for_leavings[$index])}}" @endif>
                                    </div>
                                </div>

                                <div class="col-12 pb-1"><div class="border-bottom"></div></div>
                            </div>
                        @endfor
                    </div>
                    <input type="hidden" name="total_entries" id="total_entries" value="{{$count}}"/>

                    <button class="btn btn-sm btn-primary add_field_button">Add More Attendance</button>

                    <hr>

                    <div class="row col-12">
                        <button type="button" onclick="validate();" class="btn btn-primary mr-1">{{__('language.Update')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{route('correction-requests.index', [$locale])}} @else {{route('correction-requests.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}
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
    <script src="{{ asset('js/scripts/form-correction-request-edit-script.js') }}"></script>
@endsection