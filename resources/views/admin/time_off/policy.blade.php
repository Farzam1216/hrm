@extends('layouts.contentLayoutMaster')
@section('title', $title)
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
                    <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('policy.index', [$locale])}} @else {{route('policy.index', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Back">
                        <i data-feather="chevron-left"></i>
                        <span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Back')}}</span>
                    </button>
                </div>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <a class="btn create-new btn-primary mr-1"
                        href="@if(isset($locale)) {{route('policy.assign', [$locale, $policy->id])}} @else {{route('policy.assign', ['en', $policy->id])}} @endif" data-toggle="tooltip" data-placement="top" data-original-title="Assign Policy"><i data-feather="plus"></i><span class="d-none d-lg-inline d-md-inline d-sm-none"> {{__('language.Assign')}}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table id="kt_table_1" class="dt-simple-header table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Employee Status</th>
                            <th class="text-center">Accrual Start</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedEmployees as $assignedEmployee)
                            <tr>
                                <td>{{$assignedEmployee->firstname}}</td>
                                <td class="text-center">
                                    @if($assignedEmployee->status == 1)
                                        <div class="badge badge-success">Active</div>
                                    @else
                                        <div class="badge badge-danger">In-Active</div>
                                    @endif
                                </td>
                                <td class="text-center">@if(isset($assignedEmployee->timeofftypes[0]['accrual_option'])) {{$assignedEmployee->timeofftypes[0]['accrual_option']}} @else N/A @endif</td>
                            </tr>
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