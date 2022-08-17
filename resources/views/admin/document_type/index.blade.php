@extends('layouts.contentLayoutMaster')
@section('title','Document Types')

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
            <!-- /.card-header -->
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="head-label">
                    <h6 class="mb-0"></h6>
                </div>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{url($locale.'/doc-types/create')}} @else {{url('en/doc-types/create')}} @endif'"><i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Document')}} {{__('language.Type')}}</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table id="kt_table_1" class="dt-simple-header table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('language.Name')}}</th>
                            <th>{{__('language.Status')}}</th>
                            <th>{{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doc_types as $key =>$doc_type)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$doc_type->doc_type_name}}</td>
                                <td>@if($doc_type->status==1)
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                    @else
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                    href="@if(isset($locale)){{url($locale.'/doc-types/'.$doc_type->id.'/edit')}} @else {{url('en/doc-types/'.$doc_type->id.'/edit')}} @endif" >
                                     <i data-feather="edit-2"></i></a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                       data-target="#confirm-delete{{ $doc_type->id }}" data-original-title="Close">
                                        <i data-feather="trash-2"></i> 
                                    </a>
                                </td>
                            </tr>

                            @if(($doc_type->employeeDocuments->count()) > 0)
                                <div class="modal fade" id="confirm-delete{{ $doc_type->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>{{__("language.Can't Delete Yet")}}..</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="d-block text-center">
                                                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                                </div>
                                                <h5 class="text-center">{{__("language.You've got")}}{{" ".$doc_type->employeeDocuments->count()." "}} {{__("language.document(s) using this category for")}} {{$doc_type->employeeDocuments->unique('employee_id')->count()}} employee(s).</h5>
                                                <h5>{{__("language.Don't leave them hanging; move them before deleting this")}} {{__("language.type")}}.</h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                <button  type="button" data-dismiss="modal" class="btn btn-success btn-ok">{{__('language.I\'ll fix it')}}!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="modal fade" id="confirm-delete{{ $doc_type->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Delete Document Type?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="@if(isset($locale)){{url($locale.'/doc-types',$doc_type->id)}} @else {{url('en/doc-types',$doc_type->id)}} @endif" method="post">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{ csrf_field() }}
                                                <div class="modal-body">
                                                    <h5>Are you sure you want to delete "{{ $doc_type->doc_type_name }}" document type? </h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                    <button type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table><!--end: Datatable -->
            </div><!-- /.card-body -->
        </div><!-- /.card -->
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