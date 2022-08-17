@extends('layouts/contentLayoutMaster')
@section('title','Payrolls History')
@section('heading')
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
@stop
@section('content')
    <div class="row" >
        <div class="col-lg-12">
            <div class="card __web-inspector-hide-shortcut__">
                <h3 class="card-header">Filter</h3>

                <form  method="get">
                    <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="custom-select" onchange="getFilteredData();" id="employee" name="employee_id">
                                    <option selected="" value="all">All Employee</option>
                                    @foreach ($employees as $employee)
                                        @if(!$employee->isAdmin())
                                            <option value="{{$employee->id}}">{{$employee->full_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="custom-select" onchange="getFilteredData(value);" id="month" name="month">
                                    <option selected="" value="">Select Month</option>
                                    @foreach($months as $key => $month)
                                        <option value="{{$key}}">{{$month}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="custom-select" onchange="getFilteredData(value);" id="year" name="year">
                                    <option selected="" value="">Select Year</option>
                                    @foreach($years as $key => $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-header border-bottom pb-1 pt-1 pl-0 pr-0">
                        <div class="head-label">
                            <h3 >Payrolls History</h3>
                        </div>
                        <div class="dt-buttons flex-wrap d-inline-flex">
                            <div class="" id="exportBtn">
                                <button onclick="exportPayroll()" class="btn buttons-collection btn-outline-secondary" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true" aria-expanded="false">
                                     <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share font-small-4 mr-50"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                                        Export CSV
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="row col-12 justify-content-end m-0 p-0 div"></div>
                    </div>
                    <div class="card-datatable table-responsive text-nowrap">
                        <table id="payroll-table" class="table table-sm dataTable dtr-column">
                            <thead class="head-light" id="exportColumns">
                            <tr class="text-nowrap">
                                <th> {{__('language.Name')}}</th>
                                <th> {{__('language.Mobile Number')}}</th>
                                <th> {{__('language.Month Year')}}</th>
                                <th> {{__('language.Basic Salary')}}</th>
                                <th> {{__('language.Housing Allowance')}}</th>
                                <th> {{__('language.Traveling Expanse')}}</th>
                                <th> {{__('language.Income Tax')}}</th>
                                <th> {{__('language.Bonus')}}</th>
                                <th> {{__('language.Absents')}}</th>
                                <th> {{__('language.Deduction')}}</th>
                                <th> {{__('language.Custom Deduction')}}</th>
                                <th> {{__('language.Net Payable')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> <!--end card-datatable-->
                </div>
            </div> <!--end card-->
        </div> <!--end col-lg-12-->
    </div> <!--end row-->

@section('vendor-script')
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
    <script src="{{ asset('js/scripts/payroll-history-page-filter-script.js') }}"></script>
    <script src="{{ asset('js/scripts/payroll-history-export-script.js') }}"></script>   
@endsection
@stop