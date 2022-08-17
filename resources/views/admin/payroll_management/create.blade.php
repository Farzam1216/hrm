@extends('layouts/contentLayoutMaster')
@section('title','Generate Payroll')
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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="head-label">
                    <h5 class="mb-0">@foreach($employee as $emp) {{$emp->firstname}} {{$emp->lastname}} @endforeach</h5>
                </div>
                <div class="text-right">
                    <h5 class="mb-0">{{$selectedDate->format('F - Y')}}</h5>
                </div>
            </div>

            <form method="post" id="save-form" action="@if(isset($locale)){{route('pay-roll.store', [$locale, $employeeID])}} @else {{route('pay-roll.store', ['en', $employeeID])}} @endif">
            {{ csrf_field() }}
            <input type="hidden" name="date" value="{{$selectedDate}}">
                <div class="card-body border-bottom">
                    <div class="row">
                        <input type="hidden" name="employeeID" value="{{$employeeID}}">
                        <input type="hidden" name="csv" id="csv" value="1">
                        <div class="col-md-6 form-group">
                            <label class="control-label" for="basicSalary">{{__('language.Basic Salary')}}</label><span class="text-danger"> *</span>
                            <input type="number" class="form-control numeral-mask" name="basicSalary" id="basicSalary"  placeholder="e.g. 50000"
                            >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label" for="homeAllowance">{{__('language.Home Allowance')}}</label>
                            <input type="number" class="form-control numeral-mask" name="homeAllowance" id="homeAllowance"  placeholder="e.g. 10000"
                               >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label" for="travelingExpanse">{{__('language.Traveling Expanse')}}</label>
                            <input type="number" class="form-control numeral-mask" name="travelingExpanse" id="travelingExpanse" placeholder="e.g. 30000"
                                >
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="control-label" for="incomeTax">{{__('language.Income Tax')}}</label>
                            <input type="number" class="form-control numeral-mask"name="incomeTax" id="incomeTax" placeholder="e.g. 50000"
                                >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label" for="bonus">{{__('language.Bonus')}}</label>
                            <input type="number" class="form-control numeral-mask" name="bonus" id="bonus" placeholder="e.g. 20000"
                                >
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label" for="bonus">{{__('language.Custom Deduction')}}</label>
                            <input type="number" class="form-control numeral-mask" name="custom_deduction" id="custom_deduction" placeholder="e.g. 40000"
                                >
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline card-tabs">
                    <div class="ml-1 mr-1">
                        <div class="tab-content" id="custom-tabs-two-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                                <div class="row">
                                    <div class="card-body">
                                        <div class="card-datatable table-responsive">                                   
                                            <table id="attendance-summary-table" class="table dataTable dtr-column">
                                                <thead class="thead-light">
                                                    <tr class="text-nowrap">
                                                        <th>{{__('language.Date')}}</th>
                                                        <th>{{__('language.Day')}}</th>
                                                        <th>{{__('language.Time')}} {{__('language.In')}}</th>
                                                        <th> {{__('language.Time')}} {{__('language.Out')}}</th>
                                                        <th> {{__('language.Attendance Status')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($employeeAttendance as $key=>$attendance)
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="created_at[]" value="{{$attendance['created_at']}}">
                                                                {{$attendance['created_at']}}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="day[]" value="{{$attendance['day']}}">
                                                                {{$attendance['day']}}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="time_in[]" value="{{$attendance['time_in']}}">
                                                                <input type="hidden" name="time_in_status[]" value="{{$attendance['time_in_status']}}">
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
                                                                <input type="hidden" name="time_out[]" value="{{$attendance['time_out']}}">
                                                                @if(isset($attendance['time_out']))
                                                                    {{$attendance['time_out']}}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($attendance['attendance_status']))
                                                                <select name="status[]" class="form-control">
                                                                    <option value="">{{__('language.Select')}} {{__('language.Status')}}</option>
                                                                    <option value="Present" @if(strToLower($attendance['attendance_status']) == "present") selected @endif>Present</option>
                                                                    <option value="absent"  @if(strToLower($attendance['attendance_status']) == "absent" || $attendance['attendance_status'] == "Leave Without Pay") selected @endif>Leave Without Pay</option>
                                                                    <option value="On Leave" @if(strToLower($attendance['attendance_status']) == "on leave" || str_contains($attendance['attendance_status'],'Paid Time') == true)) selected @endif>Paid Time Off</option>
                                                                    @if($countNonWorkingDays > 1)
                                                                        @if(ucfirst($nonWorkingDays[0]) == $attendance['day'] || ucfirst($nonWorkingDays[1]) == $attendance['day'] )
                                                                            <option value="weekend" @if(strToLower($attendance['attendance_status']) == "holiday" || $attendance['attendance_status'] == "Weekend") selected @endif>Weekend</option>   
                                                                        @else
                                                                            <option value="holiday" @if(strToLower($attendance['attendance_status']) == "holiday" || $attendance['attendance_status'] == "Holiday") selected @endif>Holiday</option>
                                                                        @endif 
                                                                    @else
                                                                        @if(ucfirst($nonWorkingDays[0]) == $attendance['day'] )
                                                                            <option value="weekend" @if(strToLower($attendance['attendance_status']) == "holiday" || $attendance['attendance_status'] == "Weekend") selected @endif>Weekend</option>
                                                                        @else
                                                                            <option value="holiday" @if(strToLower($attendance['attendance_status']) == "holiday" || $attendance['attendance_status'] == "Holiday") selected @endif>Holiday</option>
                                                                        @endif 
                                                                    @endif
                                                                </select>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach   
                                                </tbody>
                                            </table>
                                            <hr>
                                            <!--end: Datatable -->
                                            <div class=" d-flex">
                                                <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Generate Payroll')}}</button>
                                                <button type="button" id="save_btn" class="btn btn-outline-warning waves-effect">{{__('language.Save')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </form>
        </div> 
    </div>
</div>

@stop
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

@endsection
@section('page-script')
{{--<script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>--}}
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/validations/payroll-form-validations.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/generate-payroll-page-script.js'))}}"></script>
  <!-- Page js files -->
@endsection