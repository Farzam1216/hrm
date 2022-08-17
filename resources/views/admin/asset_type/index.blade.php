@extends('layouts/contentLayoutMaster')
@section('title','Asset-Types')

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
                <div class="text-right">
                    <a href="@if(isset($locale)){{url($locale.'/asset-types/create')}} @else {{url('en/asset-types/create')}} @endif" class="btn btn-brand btn-primary mr-1 btn-elevate btn-icon-sm">
                        <i data-feather='plus'></i>
                        {{__('language.Add')}} {{__('language.Asset')}}
                    </a>
                </div>
            </div> <!--end card-header-->
            <div class="card-datatable table-responsive pt-0" style="padding: 15px">
                <table class="dt-simple-header table dataTable dtr-column">
                    <thead class="thead-light" >
                        <tr>
                            <th>#</th>
                            <th>{{__('language.Asset')}} {{__('language.Type')}} {{__('language.Name')}} </th>
                            <th>{{__('language.Status')}}</th>
                            <th>{{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    @if($asset_types->count() > 0)
                        <tbody>
                            @foreach($asset_types as $key=>$asset_type)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$asset_type->name}}</td>
                                    <td>@if($asset_type->status==1)
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                        @else
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="text-dark" data-toggle="tooltip" data-placement="top" title="" href="@if(isset($locale)){{url($locale.'/asset-types/edit/'.$asset_type->id)}} @else {{url('en/asset-types/edit/'.$asset_type->id)}} @endif">
                                            <i data-feather="edit-2" class="mr-40"> </i>
                                        </a>

                                        <a class="text-dark" data-target="#deleteAsset_{{$asset_type->id}}" data-toggle="modal" data-placement="top">
                                            <i data-feather="trash-2" class="mr-45"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="deleteAsset_{{$asset_type->id}}" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Delete Asset Type</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Are you sure you want to delete "{{$asset_type->name}}" asset type?</h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">Cancel</button>
                                                <a class="btn btn-danger waves-effect waves-float waves-light" data-toggle="tooltip" data-placement="top" title="" href="@if(isset($locale)){{url($locale.'/asset-types/delete/'.$asset_type->id)}} @else {{url('en/asset-types/delete/'.$asset_type->id)}} @endif">
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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