@extends('layouts.contentLayoutMaster')
@section('title','Secondary Language')

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
            <div class="card-header border-bottom pb-1 pt-1">
                <div class="head-label">
                <h6 class="mb-0"></h6>
                </div>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{url($locale.'/secondarylanguage/create')}} @else {{url('en/secondarylanguage/create')}} @endif'"><i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Secondary Language')}}</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table class="dt-simple-header table dt-responsive" id="kt_table_1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Secondary Language')}}</th>
                            <th> {{__('language.Status')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($secondaryLanguage as $key => $secondary_language)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$secondary_language->language_name}}</td>
                                <td>
                                    @if($secondary_language->status==1)
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>
                                    @else
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{url($locale.'/secondarylanguage/'.$secondary_language->id.'/edit')}} @else {{url('en/secondarylanguage/'.$secondary_language->id.'/edit')}} @endif" > 
                                        <i data-feather="edit-2"></i></a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $secondary_language->id }}"  data-original-title="Close"> <i data-feather="trash-2"></i> </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="confirm-delete{{ $secondary_language->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="@if(isset($locale)){{url($locale.'/secondarylanguage',$secondary_language->id)}} @else {{url('en/secondarylanguage',$secondary_language->id)}} @endif" method="post">
                                            {{ csrf_field() }}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <div class="modal-header">
                                                <h5>Delete Secondary Language</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Are you sure you want to delete "{{ $secondary_language->language_name }}" Secondary Language?</h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light">{{__('language.Delete')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    </tbody>
                </table><!--end: Datatable -->
            </div>
        </div><!--end::Portlet-->
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