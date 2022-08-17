@extends('layouts.contentLayoutMaster')
@section('title', 'Assign Evaluation')
@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row pl-1 pr-1">
                    <div class=" col-md-5">
                        <label>Un-assigned Employees</label>
                        <div class="form-group shadow rounded">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group" id="employee-list">
                                        <!-- check for not showing hr manager in list
                                        && $employee->id != Auth::id() -->
                                        @foreach($employees as $employee)
                                            @if($employee['performance_assigned'] == '[]' && !$employee->isAdmin())
                                                <li class="list-group-item" value="{{$employee->id}}">{{$employee->full_name}}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-center" style="margin: auto;">
                        <span class="d-none d-lg-inline d-md-inline d-sm-none mt-3">
                            <a class="btn btn-primary" id="add-employee"><i class="fa fa-chevron-left"></i></a>
                            <a class="btn btn-primary" id="remove-employee"><i class="fa fa-chevron-right"></i></a>
                        </span>
                        <span class="d-lg-none d-md-none d-sm-inline">
                            <a class="btn btn-primary" id="add-employee"><i class="fa fa-chevron-up"></i></a>
                            <a class="btn btn-primary" id="remove-employee"><i class="fa fa-chevron-down"></i></a>
                        </span>
                    </div>
                    <div class=" col-md-5">
                        <label>Assigned Employees</label>
                        <div class="form-group shadow rounded">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group" id="assign-list">
                                        <!-- check for not showing hr manager in list
                                        && $employee->id != Auth::id() -->
                                        @foreach($employees as $employee)
                                            @if($employee['performance_assigned'] != '[]' && !$employee->isAdmin())
                                                <li class="list-group-item" value="{{$employee->id}}">{{$employee->full_name}}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex">
                    <button type="button" class="btn btn-primary waves-effect waves-float waves-light" id="btnSave"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Assign')}} {{__('language.Evaluation')}}</span></button>
                    <button type="button" onclick="window.location.href='@if(isset($locale)) {{route('evaluations.index', [$locale])}} @else {{route('evaluations.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect waves-float waves-light ml-1"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //append active class in list
        $(document).on('click', '.list-group-item', function() {
            $(this).toggleClass("active");
        });

        //Move active employee from left to right card and remove active class
        $(document).on('click', '#add-employee', function() {
            $('#employee-list').append($('#assign-list .active ').removeClass('active'));
        });
        //Move active employee from right to left card and remove active class
        $(document).on('click', '#remove-employee', function() {
            $('#assign-list').append($('#employee-list .active').removeClass('active'));
        });
        //Empty Add Employe Modal when hidden
        $('#add-employees').on("hidden.bs.modal", function() {
            $('#assign-list').empty();

        });
        //Ajax call to Post data
        var timeOffType = "";
        var policy = "";
        var employee = "";
        $(document).on('click', '.assign', function() {
            timeOffType = $(this).attr('time-off');
            policy = $(this).attr('policy');
        });
        $('#btnSave').click(function() {
            var employeesData = $('#assign-list li').map(function() {
                return {
                    employee_id: $(this).attr('value'),
                }
            }).get();

            var data = {
                employeesData: employeesData
            };
            $.ajax({
                url: '{{ route('evaluations.submitAssignment', ['+en+'])}}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(data) {
                    window.location.href = '/en/performance-review/evaluations';
                }
            });
        });
    });
</script>
@stop
@section('vendor-script')
  {{-- Vendor js files --}}
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
@endsection
@section('page-script')
  {{-- Page js files --}}
  <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection