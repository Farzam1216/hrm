@extends('layouts/contentLayoutMaster')
@section('title','Employee Documents')
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
                <div class="card-header border-bottom p-1">
                    <div class="head-label">
                    <h6 class="mb-0"></h6>
                    </div>
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons flex-wrap d-inline-flex">
                            <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/docs/create')}} @else {{url('en/employees/'.$employee_id.'/docs/create')}} @endif'"><i data-feather="plus"></i> {{__('language.Add')}} {{__('language.Document')}}</button>
                        </div>
                    </div>
                </div>
                    <div class="card-body">
                               
                        <table class="dt-simple-header table">
                        <thead>
                        <tr>
                                <th>#</th>
                                <th>{{__('language.Name')}}</th>
                                <th>{{__('language.File')}}</th>
                                <th>{{__('language.Document')}} {{__('language.Type')}}</th>
                                <th>{{__('language.Status')}}</th>
                            @if(Auth::user()->isAdmin() || isset($permissions['employeeDocument'][$employee_id]['employeedocument doc_name']) && $permissions['employeeDocument'][$employee_id]['employeedocument doc_name'] == "edit")
                                <th>{{__('language.Actions')}}</th>
                                @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if($docs->count() > 0)
                        @if(Auth::user()->isAdmin() || isset($permissions['employeeDocument'][$employee_id]['employeedocument doc_name']) || Auth::user()->id == $employee_id)
                                @foreach($docs as $key =>$doc)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$doc->doc_name}}</td>
                                    <td><a href="{{url($doc->doc_file)}}" download><h2><i
                                                        data-feather="file"></i></h2></a></td>
                                    <td>
                                        @foreach($doc_types as $doctype)
                                            @if($doctype->id==$doc->doc_type)
                                                {{$doctype->doc_type_name}}
                                            @endif
                                        @endforeach</td>
                                    <td>@if($doc->status==1)
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">{{__('language.Active')}}</span>
                                        @else
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">{{__('language.InActive')}}</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->isAdmin() || isset($permissions['employeeDocument'][$employee_id]['employeedocument doc_name']) && $permissions['employeeDocument'][$employee_id]['employeedocument doc_name'] == "edit")
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                            href="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/docs/'.$doc->id.'/edit')}} @else {{url('en/employees/'.$employee_id.'/docs/'.$doc->id.'/edit')}} @endif"> <i
                                            data-feather="edit-2"></i></a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                                            data-target="#confirm-delete{{ $doc->id }}" data-original-title="Close"> <i
                                                    data-feather="trash-2"></i> </a>
                                        <div class="modal fade" id="confirm-delete{{ $doc->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/docs/'.$doc->id)}} @else {{url('en/employees/docs/delete')}} @endif" method="post">
                                                        {{ csrf_field() }}
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-header">
                                                            {{__('language.Are you sure you want to delete this File?')}}
                                                        </div>
                                                        <div class="modal-header">
                                                            <h4>{{ $doc->doc_name }}</h4>
                                                        </div>
                                                        <input type="text" name="doc_id" value="{{$doc->id}}" hidden>
                                                        <input type="text" name="employee_id" value="{{$employee_id}}"
                                                                hidden>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}
                                                            </button>
                                                            <button type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            @endif
                            @endif
                        </tbody>
                        </table>
            
                    <!--end: Datatable -->
                </div>
                    <!-- /.card-body -->
            </div>
                      <!-- /.card -->
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