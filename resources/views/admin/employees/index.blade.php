@extends('layouts/contentLayoutMaster')
@section('title','Employees')
@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection
@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="card-header row justify-content-between border-bottom pt-0">
                    <div class="col-md-6">
                        <div class="head-label">
                            <h3 class="card-title col-sm-12 p-2"><b>{{$active_employees}} {{__('language.Active')}} / {{$employees->count()}} {{__('language.Employees')}}</b></h3>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row justify-content-end">
                            <div class=" mt-1">
                                <select class="form-control" id="filter">
                                    <option value="select">{{__('language.Select')}} {{__('language.Employees')}}</option>
                                    <option value="{{url($locale.'/employees')}}">{{__('language.All')}} {{__('language.Employees')}}</option>
                                    @if($filters->count() > 0)
                                    @foreach($filters as $filter)
                                    <option value="{{url($locale.'/employees/'.$filter->id)}}">{{$filter->employment_status}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            @if(Auth::user()->isAdmin() || isset($permissions['employee']['all']) && array_intersect(['manage employees store'], $permissions['employee']['all'] ))
                            <div class="dropdown ml-1 mt-1">
                                <div class="btn-group mr-1">
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather='plus-circle' class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/employee/create')}} @else {{url('en/employee/create')}} @endif">
                                            <i data-feather="plus"></i>
                                            {{__('language.Add')}} {{__('language.Employee')}}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="@if(isset($locale)) {{route('import.employee.create', [$locale])}} @else {{route('import.employee.create', ['en'])}} @endif">
                                            <i data-feather="upload"></i>
                                            <span class="d-none d-lg-inline d-md-inline d-sm-none"> Import Employees</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive pt-0">
                    <table id="employees-table" class="table table-sm dataTable dtr-column">

                        <thead class="thead-light">
                            <tr>
                                <th>{{__('language.Name')}}</th>
                                <th>{{__('language.Email')}}</th>
                                <!-- <th>{{__('language.Mobile')}} </th> -->
                                <th>{{__('language.Designation')}}</th>
                                <!-- <th>{{__('language.Department')}}</th> -->
                                <th> {{__('language.Employement Status')}}</th>
                                <th> {{__('language.Status')}}</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-left align-items-center">
                                            <div class="avatar-wrapper">
                                                <div class="avatar mr-1">
                                                    <img class="round" src="{{asset($employee->picture)}}" onerror="this.src ='{{asset('asset/media/users/default3.png')}}';" alt="avatar" height="40" width="40">
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bold">
                                                    {{$employee->firstname}} {{$employee->lastname}}
                                                </span>
                                                <!-- <small class="emp_post text-muted">
                                                    </small> -->
                                            </div>
                                        </div>
                                    </td>
                                    <td> {{$employee->official_email}}</td>
                                    <!-- <td>{{$employee->contact_no}} </td> -->

                                    <td>{{isset($employee->designation) ?  $employee->designation->designation_name :''}} </td>
                                    <!-- <td>{{isset($employee->department) ? $employee->department->department_name : ''}}</td> -->
                                    <td>{{isset($employee->employmentStatus) ? $employee->employmentStatus->employment_status : ''}}
                                        <!-- <span class="badge badge-pill badge-light-primary mr-1">Active</span> -->
                                    </td>
                                    <td>
                                        @if($employee->status == 1)
                                            <span class="badge badge-light-success">Active</span>
                                        @else
                                            <span class="badge badge-light-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(Auth::user()->isAdmin() || (isset($permissions['employee'][$employee->id])
                                            && isset($basicPermissions['employee'][$employee->id])
                                            && $basicPermissions['employee'][$employee->id] == "true")
                                            || isset($permissions['education'][$employee->id])
                                            || isset($permissions['educationType'][$employee->id])
                                            || isset($permissions['department'][$employee->id])
                                            || isset($permissions['location'][$employee->id])
                                            || isset($permissions['secondaryLanguage'][$employee->id])
                                            || isset($permissions['assets'][$employee->id])
                                            || isset($permissions['visaType'][$employee->id])
                                            || isset($permissions['employeeVisa'][$employee->id])
                                            || isset($permissions['employeeDocument'][$employee->id])
                                            || isset($permissions['benefits'][$employee->id])
                                            || isset($permissions['benefitGroup'][$employee->id])
                                            || isset($permissions['dependents'][$employee->id])
                                            || isset($permissions['benefitPlans'][$employee->id])
                                            || (isset($permissions['timeofftype'][$employee->id])
                                            && isset($basicPermissions['timeofftype'][$employee->id])
                                            && $basicPermissions['timeofftype'][$employee->id] == "true")
                                            || isset($permissions['policy'][$employee->id])
                                            || isset($permissions['tasks'][$employee->id]))
                                            @if(Auth::user()->id == $employee->id) 
                                                @if(Auth::user()->can_mark_attendance == 1 && $permissions['attendance'])
                                                <a class="text-dark" data-toggle="tooltip" data-placement="top" title="View Employee Attendance" href="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/employee-attendance')}} @else {{url('en/employees/'.$employee->id.'/employee-attendance')}} @endif">
                                                    <i data-feather='file-minus'></i>
                                                </a>
                                                @endif
                                            @else
                                            <a class="text-dark" data-toggle="tooltip" data-placement="top" title="View Employee Attendance" href="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/employee-attendance')}} @else {{url('en/employees/'.$employee->id.'/employee-attendance')}} @endif">
                                                <i data-feather='file-minus'></i>
                                            </a>
                                            @endif  
                                            <a class="text-dark" href="@if(isset($locale)){{url($locale.'/employee/edit/'.$employee->id)}}
                                                @else {{url('en/employee/edit/'.$employee->id)}} @endif" >
                                                <i data-feather="edit-2" class="mr-40"></i>
                                            </a>
                                            <a class="text-dark" href="#">
                                                <i data-feather="trash-2" class="mr-45"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
@endsection
@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
<script>
    $('#employees-table').DataTable({
        "order": [],
        "drawCallback": function( settings )
        {
            feather.replace();
        }
    });

    $("#save_btn").on('click', function(){
        $("#csv").val('0')
        $("#save-form").submit();
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#filter").change(function(e) {
            var url = $(this).val(); // get selected value

            if (url) { // require a URL
                window.location = url; // redirect
            }
            return false;
        });
        // $('.datatable').DataTable();

    });
</script>
<script type="text/javascript">
    $("input.zoho").click(function(event) {
        if ($(this).is(":checked")) {
            $("#div_" + event.target.id).show();
        } else {
            $("#div_" + event.target.id).hide();
        }
    });
</script>
<script type="text/javascript">
    $("input.zoho").click(function(event) {
        if ($(this).is(":checked")) {
            $("#div_" + event.target.id).show();
        } else {
            $("#div_" + event.target.id).hide();
        }
    });
</script>

@endsection


@stop