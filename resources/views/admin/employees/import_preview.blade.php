@extends('layouts.contentLayoutMaster')
@section('title','Import Preview')
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
            <form id="import-employee-form" action="@if(isset($locale)) {{route('import.employee.store', [$locale])}} @else {{route('import.employee.store', ['en'])}} @endif" method="post">
            {{csrf_field()}}
                <div class="card-body">
                    <table id="kt_table_1" class="table table-sm table-responsive dt-simple-header">
                        <thead>
                            <tr>
                                @foreach ($data[0] as $key => $value)
                                    <th>
                                        <select class="form-control text-capitalize text-nowrap required" style="width: 230px;" name="fields[{{$key}}]">
                                            <option value="">...No match,select a field...</option>
                                            @foreach ($db_fields as $db_field)
                                                @if($db_field == "employee_no" || $db_field == "firstname" || $db_field == "lastname" || $db_field == "official_email") 
                                                    <option class="text-danger" value="{{$db_field}}" @if ($key == $db_field) selected  @endif> {{$db_field}}<span class="text-danger">*</span></option>
                                                @else
                                                    <option value="{{$db_field}}" @if ($key == $db_field) selected  @endif> {{$db_field}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key =>$row)
                                <tr>
                                    @foreach ($row as $key => $value)
                                    <input type="hidden" value="{{ $value }}" name="values[]">
                                        <td class="text-nowrap">{{ $value }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                    </table>

                    <hr>

                    <div class="row col-12">
                        <button type="submit" id="preview" class="btn btn-primary waves-effect waves-float waves-light" data-toggle="modal" data-target="#large"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="check-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none"> Upload</span></button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)) {{route('import.employee.create',[$locale])}} @else {{route('import.employee.create',['en'])}} @endif'" class="btn btn-outline-warning waves-effect ml-1"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="x-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">Cancel</span>
                        </button>
                    </div>
                </div>
            </form>
           <!-- /.card-body -->
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
