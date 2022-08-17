@extends('layouts/contentLayoutMaster')
@section('title','Assets')
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

{{--    @if(session('success'))--}}
{{--        <div class="alert alert-success" role="alert">--}}
{{--            <a href="#" class="close" style="margin-right: 10px; margin-top: 5px" data-dismiss="alert" aria-label="close">&times;</a>--}}
{{--            <div class="alert-body">{{session('success')}}</div>--}}
{{--        </div>--}}
{{--    @endif--}}
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header border-bottom pb-1 pt-1">
                    <div class="head-label">
                        <h6 class="mb-0"></h6>
                    </div>
                    @if(Auth::user()->isAdmin()
|| (isset($permissions['assets'][$employee_id]['asset asset_category']) && ($permissions['assets'][$employee_id]['asset asset_category'] != "view"))
&& (isset($permissions['assets'][$employee_id]['asset asset_description']) && ( $permissions['assets'][$employee_id]['asset asset_description'] != "view"))
&& (isset($permissions['assets'][$employee_id]['asset serial']) && ( $permissions['assets'][$employee_id]['asset serial'] != "view"))
&& (isset($permissions['assets'][$employee_id]['asset assign_date']) && ( $permissions['assets'][$employee_id]['asset assign_date'] != "view"))
)
                    <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex">
                        <a href="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets/create')}} @else {{url('en/employees/'.$employee_id.'/assets/create')}} @endif" class="btn create-new btn-primary mr-1 waves-effect waves-float waves-light">
                            <i data-feather='plus'></i>
                            {{__('language.Add')}} {{__('language.Asset')}}
                        </a>
                    </div>
                        @endif
                </div> <!--end card-header-->
                <div class="card-datatable table-responsive pt-0 p-1">
{{--                    @if($assets->count() > 0)--}}
                    <table class="dt-simple-header table dataTable dtr-column">
                        <thead class="thead-light">
                        <tr>
                            @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]))
                                <th>#</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset asset_category']))
                                <th> {{__('language.Category')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset asset_description']))
                                <th> {{__('language.Description')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset serial']))
                                <th>{{__('language.Serial')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset assign_date']))
                                <th>{{__('language.Assign')}} {{__('language.Date')}}</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset return_date']))
                                <th>{{__('language.Return')}} {{__('language.Date')}}</th>
                            @endif
                                <th>{{__('language.Actions')}}</th>
                        </tr>
                        </thead>
                        @if($assets->count() > 0)
                            <tbody>
                            @foreach($assets as $key=>$asset)
                                <tr>
                                    @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]))
                                        <td>{{$key+1}}</td>
                                    @endif
                                        @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset asset_category']))
                                            <td>{{$asset->asset_type->name}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset asset_description']))
                                            <td>{{$asset->asset_description}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset serial']))
                                            <td>{{$asset->serial}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset assign_date']))
                                            <td>{{$asset->assign_date}}</td>
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($permissions['assets'][$employee_id]['asset return_date']))
                                            @if(isset($asset->return_date))
                                                <td>{{$asset->return_date}}</td>
                                            @else
                                            <td>--</td>
                                            @endif
                                        @endif
                                        @if(Auth::user()->isAdmin()
                  || (isset($permissions['assets'][$employee_id]) && (in_array('edit', $permissions['assets'][$employee_id]) || in_array('edit_with_approval', $permissions['assets'][$employee_id]))))
                                            <td class="text-nowrap">
                                                <a class="text-dark" data-toggle="tooltip" data-placement="top" title="" href="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets/edit/'.$asset->id)}}
                                                @else {{url('en/employees/'.$employee_id.'/assets/edit/'.$asset->id)}} @endif">
                                                    <i data-feather="edit-2" class="mr-40"> </i></a>
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                                   data-target="#confirm-delete{{ $asset->id }}"
                                                   data-original-title="Close"> <i data-feather="trash-2" class="mr-45"></i> </a>
                                                <div class="modal fade" id="confirm-delete{{ $asset->id }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets/'.$asset->id.'/delete')}} @else {{url('en/employees/'.$employee_id.'/assets/'.$asset->id.'/delete')}} @endif"
                                                                  method="post">
                                                                {{ csrf_field() }}
                                                                <div class="modal-body">
                                                                    <b>Are you sure you want to delete this "{{$asset->asset_type->name}}" task category? </b>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                            class="btn btn-primary">{{__('language.Delete')}}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                </tr>
                            @endforeach
                            <!-- <tr> {{__('language.No Asset Found')}}</tr> -->
                            @endif
                            </tbody>
                    </table>
{{--                    @endif--}}
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