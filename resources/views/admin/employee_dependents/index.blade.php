@extends('layouts/contentLayoutMaster')
@section('title','Employee Dependents')

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
                <div class="card-header border-bottom pb-1 pt-1">
                    <div class="head-label">
                        <h6 class="mb-0"></h6>
                    </div>
                    <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex">
                        <a href="@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents/create')}} @else {{url('en/employees/'.$emp_id.'/dependents/create')}} @endif"
                           class="btn create-new btn-primary mr-1 waves-effect waves-float waves-light">
                            <i data-feather='plus'></i>
                            {{__('language.Add')}} {{__('language.Employee')}} {{__('language.Dependent')}}
                        </a>
                    </div>
                </div> <!--end card-header-->
                <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                    <table class="dt-simple-header table dataTable dtr-column">
                        <thead class="head-light">
                        <tr>
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]))
                                <th>#</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent first_name']) || isset($permissions['dependents'][$emp_id]['employeedependent last_name']))
                                <th> {{__('language.Name')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent relationship']))
                                <th> {{__('language.Relationship')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent gender']))
                                <th> {{__('language.Gender')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SSN']))
                                <th> {{__('language.SSN')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SIN']))
                                <th> {{__('language.SIN')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent date_of_birth']))
                                <th> {{__('language.Birth Date')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin()
                                 || (isset($permissions['dependents'][$emp_id])
                                 && (in_array('edit', $permissions['dependents'][$emp_id])
                                 | in_array('edit_with_approval', $permissions['dependents'][$emp_id]))))
                                <th> {{__('language.Actions')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($employeeDependents)>0 )
                            @foreach($employeeDependents as $key => $employeeDependent)
                                <tr>
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]))
                                        <td>{{$key+1}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent first_name']) || isset($permissions['dependents'][$emp_id]['employeedependent last_name']))
                                        <td>{{$employeeDependent->first_name}} {{$employeeDependent->last_name}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent relationship']))
                                        <td>{{$employeeDependent->relationship}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent gender']))
                                        <td>{{$employeeDependent->gender}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SSN']))
                                        <td>{{$employeeDependent->SSN}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent SIN']))
                                        <td>{{$employeeDependent->SIN}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($permissions['dependents'][$emp_id]['employeedependent date_of_birth']))
                                        <td>{{$employeeDependent->date_of_birth}}</td>
                                    @endif
                                    @if(Auth::user()->isAdmin()
         || (isset($permissions['dependents'][$emp_id]) && (in_array('edit', $permissions['dependents'][$emp_id]) || in_array('edit_with_approval', $permissions['dependents'][$emp_id]))))
                                        <td class="text-nowrap">
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                               onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents/'.$employeeDependent->id.'/edit')}} @else {{url('en/employees/'.$emp_id.'/dependents/'.$employeeDependent->id.'/edit')}} @endif'"
                                               data-original-title="Edit"> <i data-feather="edit-2" class="mr-40"> </i></a>
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                               data-target="#confirm-delete{{ $employeeDependent->id }}"
                                               data-original-title="Close"> <i data-feather="trash-2"></i> </a>

                                            <div class="modal fade" id="confirm-delete{{ $employeeDependent->id }}" tabindex="-1"
                                                 role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents/'.$employeeDependent->id)}} @else {{url('en/employees/'.$emp_id.'/dependents/'.$employeeDependent->id)}} @endif"
                                                              method="post">
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            {{ csrf_field() }}
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete This Dependent? </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                        class="btn btn-danger waves-effect waves-float waves-light">{{__('language.Delete')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="modal fade" id="confirm-delete{{ $employeeDependent->id }}"--}}
{{--                                                 tabindex="-1" role="dialog" aria-labelledby="myModalLabel"--}}
{{--                                                 aria-hidden="true">--}}
{{--                                                <div class="modal-dialog">--}}
{{--                                                    <div class="modal-content">--}}
{{--                                                        <form action="@if(isset($locale)){{url($locale.'/employees/'.$emp_id.'/dependents/'.$employeeDependent->id)}} @else {{url('en/employees/'.$emp_id.'/dependents/'.$employeeDependent->id)}} @endif"--}}
{{--                                                              method="post">--}}
{{--                                                            <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                            {{ csrf_field() }}--}}
{{--                                                            <div class="modal-header">--}}
{{--                                                                {{__('language.Are you sure you want to delete this Dependent?')}}--}}
{{--                                                            </div>--}}
{{--                                                            <div class="modal-header">--}}
{{--                                                                <h4>{{ $employeeDependent->first_name }} {{$employeeDependent->last_name}}</h4>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="modal-footer">--}}
{{--                                                                <button type="button" class="btn btn-outline-warning waves-effect"--}}
{{--                                                                        data-dismiss="modal">{{__('language.Cancel')}}</button>--}}
{{--                                                                <button type="submit"--}}
{{--                                                                        class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>--}}
{{--                                                            </div>--}}
{{--                                                        </form>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div> <!--end card-datatable-->
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

@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection
@stop