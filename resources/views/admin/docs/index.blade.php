@extends('layouts.contentLayoutMaster')
@section('title','Documents')

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
                        onclick="window.location.href='@if(isset($locale)){{url($locale.'/documents/create')}} @else {{url('en/documents/create')}} @endif'"><i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Document')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table id="kt_table_1" class="dt-simple-header table">
                    <thead>
                        <tr>
                            <th>{{__('language.Document')}} {{__('language.Name')}}</th>
                            <th>{{__('language.Status')}}</th>
                            <th>{{__('language.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td>
                                    <a class="ml-25 text-truncate font-weight-bold" target="_blank" href="{{asset($file->url)}}">{{ $file->name }}</a>
                                </td>
                                <td>
                                    @if($file->status == 1)
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                    @else
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                    @endif
                                </td>
                                <td class="row">
                                    <a data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="Edit Document"
                                    class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                    href="@if(isset($locale)){{url($locale.'/documents/'.$file->id.'/edit')}} @else {{url('en/documents/'.$file->id.'/edit')}} @endif">
                                        <span data-feather="edit-2"></span>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete-{{ $file->id }}"  data-original-title="Close"> <i data-feather="trash-2"></i> </a>
                                </td>
                            </tr>
                            <form action="@if(isset($locale)){{url($locale.'/documents',$file->id)}} @else {{url('en/documents',$file->id)}} @endif" method="post">
                                <input name="_method" type="hidden" value="DELETE">
                                {{ csrf_field() }}
                                <div class="modal fade" id="confirm-delete-{{ $file->id }}" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Delete Document</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Are you sure you want to delete "{{ $file->name }}" document?</h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
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