@extends('layouts/contentLayoutMaster')
@section('title','Show')
@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body border-bottom pb-0">
                <div class="head-label">
                    <h5 class="font-weight-normal">
                        <strong>Attendance Date:</strong>
                        {{$date->format('d-m-Y')}}
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <table id="kt_table_1" class="table dt-simple-header">
                    <thead>
                        <tr>
                            <th>Sr.#</th>
                            <th>Time In</th>
                            <th>Time out</th>
                            <th>Reason For Leaving</th>
                            <th>Attendance Status</th>
                            @if(Auth::user()->isAdmin() || isset($permissions['attendance']['all']) && array_intersect(['manage employees attendance'], $permissions['attendance']['all']))
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee['employeeAttendance'] as $key => $attendance)
                            @if($attendance->created_at->format('Y-m-d') == $date->format('Y-m-d'))
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @if($attendance->time_in)
                                            {{$attendance->time_in}}
                                            @if($attendance->time_in_status != null)
                                                <div class="avatar bg-danger">
                                                    <div class="badge">Late</div>
                                                </div>
                                            @endif
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
                                    @if(Auth::user()->isAdmin() || isset($permissions['attendance']['all']) && array_intersect(['manage employees attendance'], $permissions['attendance']['all']))
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-sm dropdown-toggle hide-arrow  waves-effect waves-float waves-light" data-toggle="dropdown" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a>

                                                <div class="dropdown-menu dropdown-menu-right" style="">
                                                    <a href="@if(isset($locale)) {{route('employees.employee-attendance.edit', [$locale, $attendance->employee_id, $attendance->id])}} @else {{route('employees.employee-attendance.edit', ['en', $attendance->employee_id, $attendance->id])}} @endif" class="dropdown-item text-left">Update</a><a href="@if(isset($locale)) {{route('employees.employee-attendance.history', [$locale, $attendance->employee_id, $attendance->id])}} @else {{route('employees.employee-attendance.history', ['en', $attendance->employee_id, $attendance->id])}} @endif" class="dropdown-item text-left">History</a>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
@stop