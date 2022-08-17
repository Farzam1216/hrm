@extends('layouts/contentLayoutMaster')
@section('title','Employee Attendance')
@section('heading')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card __web-inspector-hide-shortcut__">
                <h3 class="card-header">Filter</h3>
                <form class="mb-2" id="delete-employee-monthly-attendance" action="@if(isset($locale)){{url($locale.'/delete-monthly-attendance')}} @else {{url('en/delete-monthly-attendance')}} @endif" method="post">
                    {{ csrf_field() }}
                    <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" id="employee" name="employee_id">
                                    <option selected="" value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        @if(!$employee->isAdmin())
                                            <option value="{{$employee->id}}">{{$employee->full_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" id="month" name="month">
                                    <option selected="" value="">Select Month</option>
                                    @foreach($months as $key => $month)
                                        <option value="{{$key}}">{{$month}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" id="year" name="year">
                                    <option selected="" value="">Select Year</option>
                                    @foreach($years as $key => $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-primary btn-block">Delete</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!--end col-lg-12-->
    </div> <!--end row-->

@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-delete-employee-monthly-attendance-validation.js')) }}"></script>
@endsection
@stop
