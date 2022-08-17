@extends('layouts.contentLayoutMaster')
@section('title', 'Assign Form')
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
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="head-label">
                    <h6 class="mb-0">{{ucwords($form->name)}}</h6>
                </div>
            </div>
            <div class="card-body pt-1">
                <div class="row">
                    <div class="col-md-5">
                        <label>Un-assigned Employees</label>
                        <div class="form-group shadow rounded">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-group" id="employee-list">
                                        @php $employeeIds = ''; $i = 0; @endphp
                                        @foreach($employees as $employee)
                                            @if(!$employee->isAdmin())
                                                @if($employee->assignedForm == null || isset($employee->assignedForm->form_id) && $employee->assignedForm->form_id != $form->id)
                                                    <li class="list-group-item" value="{{$employee->id}}">{{$employee->full_name}} @if(isset($employee->assignedForm->form)) ( {{ucwords($employee->assignedForm->form->name)}} ) @php if($employeeIds){$employeeIds = $employeeIds.','.$employee->id;} else {$employeeIds = $employee->id;} @endphp @endif</li>
                                                @endif
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
                                            @if(!$employee->isAdmin())
                                                @if(isset($employee->assignedForm->form_id) && $employee->assignedForm->form_id == $form->id)
                                                    <li class="list-group-item" value="{{$employee->id}}">{{$employee->full_name}}</li>
                                                @endif
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
                    <button type="button" class="btn btn-primary waves-effect waves-float waves-light" id="btnSave"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Assign')}} {{__('language.Form')}}</span></button>
                    <button type="button" onclick="window.location.href='@if(isset($locale)) {{route('forms.index', [$locale])}} @else {{route('forms.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect waves-float waves-light ml-1"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Assign Form Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mt-1">
                <input type="hidden" value="no" id="confirmation">
                <h5>This action will update already assigned form of employee. Do you still want to perform this action?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.No')}}</button>
                <button type="submit" onclick="$('#confirmation').val('yes'); $('#btnSave').click();" class="btn btn-primary waves-effect waves-float waves-light btn-ok">{{__('language.Yes')}}</button>
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
                employeesData: employeesData,
                form_id: {!! $form->id !!}
            };

            check = false;
            employeeIds = '{!! $employeeIds !!}'.split(',');

            $.each(employeesData, function(index, employee){
                if (employeeIds.includes(employee.employee_id)) {
                    check = true;
                }
            });

            if ($("#confirmation").val() == 'no' && check == true) {
                $("#confirm-modal").modal('show');
            }

            if ($("#confirmation").val() == 'yes' || check == false) {
                $.ajax({
                    url: '{{ route('forms.submitAssignment', ['+en+', $form->id]) }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        window.location.href = '/en/performance-review/forms';
                    }
                });
            }
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