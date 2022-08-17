@extends('layouts/contentLayoutMaster')
@section('title','Correction Request Decision')
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
                <div class="card-header p-0">
                    <div class="head-label"> 
                        <h6>{{$correctionRequest->employee->firstname}} {{$correctionRequest->employee->lastname}} Old Attendance</h6>
                    </div>
                    <div class="dt-action-buttons dt-buttons flex-wrap d-inline-flex"> 
                        <h6>Date: {{$date->format('d-m-Y')}}</h6>
                    </div>
                </div>
                <hr class="mt-0">

                <table id="kt_table_1" class="table table-sm dt-simple-header">
                    <thead>
                        <tr>
                            <th>Sr.#</th>
                            <th>Time In</th>
                            <th>Time out</th>
                            <th>Time In Status</th>
                            <th>Reason</th>
                            <th>Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $key = 1; @endphp
                        @foreach($correctionRequest->employee['employeeAttendance'] as $attendance)
                            @if($attendance->created_at->format('Y-m-d') == $date->format('Y-m-d'))
                                <tr>
                                    <td>
                                        {{$key++}}
                                    </td>
                                    <td>
                                        @if($attendance->time_in)
                                            {{$attendance->time_in}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->time_out)
                                            {{$attendance->time_out}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->time_in_status)
                                            {{$attendance->time_in_status}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->reason_for_leaving)
                                            {{$attendance->reason_for_leaving}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->attendance_status)
                                            {{$attendance->attendance_status}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class="card-header p-0 mt-4">
                    <div class="head-label"> 
                        <h6>Requested Attendance Changes</h6>
                    </div>
                    <div class="dt-action-buttons  dt-buttons flex-wrap d-inline-flex"> 
                        <h6>Date: {{$date->format('d-m-Y')}}</h6>
                    </div>
                </div>
                <hr class="mt-0">

                <table id="kt_table_1" class="table table-sm dt-simple-header">
                    <thead>
                        <tr>
                            <th>Sr.#</th>
                            <th>Time In</th>
                            <th>Time out</th>
                            <th>Time In Status</th>
                            <th>Reason</th>
                            <th>Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $time_ins = explode(',',$correctionRequest->time_in); @endphp
                        @php $time_outs = explode(',',$correctionRequest->time_out); @endphp
                        @php $time_in_statuses = explode(',',$correctionRequest->time_in_status); @endphp
                        @php $reason_for_leavings = explode(',',$correctionRequest->reason_for_leaving); @endphp
                        @php $attendance_statuses = explode(',',$correctionRequest->attendance_status); @endphp
                        @php $dataCount = $time_ins; @endphp

                        @foreach($dataCount as $key => $count)
                            <tr>
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    @if($time_ins[$key] != ' ')
                                        {{$time_ins[$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($time_outs[$key] != ' ')
                                        {{$time_outs[$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($time_in_statuses[$key] != ' ')
                                        {{$time_in_statuses[$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($reason_for_leavings[$key] != ' ')
                                        {{$reason_for_leavings[$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($attendance_statuses[$key] != ' ')
                                        {{$attendance_statuses[$key]}}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form class="pt-1" id="correction-request-decision-form" action="@if(isset($locale)) {{route('correction-requests.decision.store', [$locale, $correctionRequest->id])}} @else {{route('correction-requests.decision.store', ['en', $correctionRequest->id])}} @endif" method="post">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{$correctionRequest->employee->id}}"/>
                    <input type="hidden" name="date" value="{{$date->format('Y-m-d')}}"/>

                    <div class="row">
                        <div class="form-group col-6">
                            <label for="decision">Decision <span class="text-danger">*</span></label>
                            <select class="form-control" id="decision" name="decision">
                                <option value="">Select Decision</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="row col-12">
                        <button type="submit" class="btn btn-primary mr-1">{{__('language.Submit')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{route('correction-requests.index', [$locale])}} @else {{route('correction-requests.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}
                        </button>
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
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-correction-request-decision.js')) }}"></script>
@endsection