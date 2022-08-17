@extends('layouts/contentLayoutMaster')
@section('title','Employees Attendance History')
@section('heading')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="border-bottom">
                        <div class="d-flex justify-content-between">
                            <div class="head-label"> 
                                <h6>Latest Record</h6>
                            </div>
                            <div class="head-label"> 
                                <h6>Attendance Date: {{$employeeAttendance->created_at->format('d-m-Y')}}</h6>
                            </div>
                        </div>
                    </div> <!--end card-header-->
                    <div class="card-datatable table-responsive">
                        <table class="table table-sm dataTable dt-simple-header">
                            <thead class="thead-light">
                                <tr>
                                    <th> {{__('language.Name')}} </th>
                                    <th> {{__('language.Time')}} {{__('language.In')}} </th>
                                    <th> {{__('language.Time')}} {{__('language.Out')}} </th>
                                    <th> {{__('language.Time In Status')}} </th>
                                    <th> {{__('language.Attendnace Status')}} </th>
                                    <th> {{__('language.Reason For Leaving')}} </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {{$employeeAttendance->employee->firstname}} {{$employeeAttendance->employee->lastname}} </td>
                                    <td> {{$employeeAttendance->time_in}} </td>
                                    <td> {{$employeeAttendance->time_out}} </td>
                                    <td> {{$employeeAttendance->time_in_status}} </td>
                                    <td> {{$employeeAttendance->attendance_status}} </td>
                                    <td> {{$employeeAttendance->reason_for_leaving}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!--end card-datatable-->
                </div>
            </div> <!--end card-->

            <div class="card">
                <div class="card-body">
                    <div class="border-bottom">
                            <div class="head-label"> 
                                <h6>Change History</h6>
                            </div>
                    </div> <!--end card-header-->
                    <div class="card-datatable table-responsive">
                        <table class="table table-sm dataTable dt-simple-header">
                            <thead class="thead-light">
                                <tr>
                                    <th> {{__('language.Time')}} {{__('language.In')}} </th>
                                    <th> {{__('language.Time')}} {{__('language.Out')}} </th>
                                    <th> {{__('language.Time In Status')}} </th>
                                    <th> {{__('language.Attendnace Status')}} </th>
                                    <th> {{__('language.Reason For Leaving')}} </th>
                                    <th> {{__('language.Changed By')}} </th>
                                    <th> {{__('language.Updated')}} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employeeAttendance['attendanceHistory'] as $history)
                                    <tr>
                                        <td> {{$history->time_in}} </td>
                                        <td> {{$history->time_out}} </td>
                                        <td> {{$history->time_in_status}} </td>
                                        <td> {{$history->attendance_status}} </td>
                                        <td> {{$history->reason_for_leaving}} </td>
                                        <td> {{$history->employee->firstname}} {{$history->employee->lastname}} </td>
                                        <td> {{$history->created_at->format('d-m-Y')}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!--end card-datatable-->
                </div>
            </div> <!--end card-->
        </div> <!--end col-lg-12-->
    </div> <!--end row-->

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
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection
@stop
