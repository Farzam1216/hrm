@extends('layouts.contentLayoutMaster')
@section('title','Time Off Policy')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="head-label">
                    <h6 class="mb-0"></h6>
                </div>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1"
                        onclick="window.location.href='@if(isset($locale)){{route('policy.create', [$locale])}} @else {{route('policy.create', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Add Time Off"><i data-feather="plus"></i><span class="d-none d-lg-inline d-md-inline d-sm-none"> {{__('language.Add')}} {{__('language.Time Off')}}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table id="kt_table_1" class="dt-simple-header table">
                    <thead>
                        <tr>
                            <th> {{__('language.Time off Type')}}</th>
                            <th> {{__('language.Policy Name')}}</th>
                            <th> {{__('language.Policy Type')}}</th>
                            <th> {{__('language.Assign')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($policies as $key => $policyLevel)
                            <tr>
                                <td>{{$policyLevel->timeoff->time_off_type_name}}</td>
                                <td>
                                    @if(isset($policyLevel->policy_name)){{$policyLevel->policy_name}} @else <p>this time off time require no policy</p> @endif
                                </td>
                                <td>@if(isset($policyLevel->policy_type)) {{ucwords($policyLevel->policy_type)}} @else N/A @endif</td>
                                <td>
                                    <a type="button" href="@if(isset($locale)) {{route('policy.assigned_employees', [$locale, $policyLevel->id])}} @else {{route('policy.assigned_employees', ['en', $policyLevel->id])}} @endif" class="btn btn-primary btn-sm assign">Assign</a>
                                </td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" onclick="window.location.href='@if(isset($locale)){{route('policy.edit', [$locale, $policyLevel->id])}} @else {{route('policy.edit', ['en', $policyLevel->id])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i data-feather="edit-2"></i></a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $policyLevel->id }}"><i data-feather="trash-2"></i> </a>
                                </td>
                            </tr>

                            <div class="modal fade text-left" id="confirm-delete{{ $policyLevel->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="@if(isset($locale)){{route('policy.destroy', [$locale, $policyLevel->id])}} @else {{route('policy.destroy', [$locale, $policyLevel->id])}} @endif" method="post">
                                            {{ csrf_field() }}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <div class="modal-header">
                                                <h4>Time Off Policy</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete "{{ $policyLevel->policy_name }}" time off policy?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect waves-float waves-light" data-dismiss="modal"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                                                <button type="submit" class="btn btn-danger btn-ok"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='trash-2'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Delete')}}</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
