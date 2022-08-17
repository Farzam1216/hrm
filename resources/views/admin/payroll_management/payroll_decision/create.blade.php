@extends('layouts/contentLayoutMaster')
@section('title','Payroll Decision')

@section('vendor-style')
    {{-- Vendor Css files --}}
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
            <div class="card-body">
                <button type="reset" onclick="window.location.href='@if(isset($locale)){{url($locale.'/payroll-management')}} @else {{url('en/payroll-management')}} @endif'" class="btn btn-primary waves-effect waves-float waves-light">
                    <i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Back')}}</span>
                </button>

                <hr>

                <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                    <table id="payroll-table" class="dt-simple-header table table-sm dataTable dtr-column">
                        <thead class="head-light">
                            <tr class="text-nowrap">
                                <th> {{__('language.Name')}}</th>
                                <th> {{__('language.Mobile Number')}}</th>
                                <th> {{__('language.Month')}}</th>
                                <th> {{__('language.Basic Salary')}}</th>
                                <th> {{__('language.Housing Allowance')}}</th>
                                <th> {{__('language.Traveling Expanse')}}</th>
                                <th> {{__('language.Income Tax')}}</th>
                                <th> {{__('language.Bonus')}}</th>
                                <th> {{__('language.Absents')}}</th>
                                <th> {{__('language.Deduction')}}</th>
                                <th> {{__('language.Net Payable')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                                <tr class="text-nowrap">
                                    <td>{{$payroll->employee->firstname}} {{$payroll->employee->lastname}}</td>
                                    <td>{{$payroll->employee->contact_no}}</td>
                                    <td>
                                        {{\Carbon\Carbon::parse('01-'.$payroll->month_year)->format('F-Y')}}
                                    </td>
                                    <td>{{$payroll->basic_salary}}</td>
                                    <td>{{$payroll->home_allowance}}</td>
                                    <td>{{$payroll->travel_expanse}}</td>
                                    <td>{{$payroll->income_tax}}</td>
                                    <td>{{$payroll->bonus}}</td>
                                    <td>{{$payroll->absent_count}}</td>
                                    <td>{{$payroll->deduction}}</td>
                                    <td>{{$payroll->net_payable}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <form id="payroll-decision-form" class="mt-1" @if(isset($ids)) action="@if(isset($locale)){{route('payroll-management.decision.store', [$locale, 'multiple'])}} @else {{route('payroll-management.decision.store', ['en', 'multiple'])}} @endif" @endif @if(isset($id)) action="@if(isset($locale)){{route('payroll-management.decision.store', [$locale, 'single'])}} @else {{route('payroll-management.decision.store', ['en', 'single'])}} @endif" @endif method="post">
                    {{ csrf_field() }}

                    @if(isset($ids))
                        <input type="hidden" name="ids" value="{{$ids}}">
                    @endif

                    @if(isset($id))
                        <input type="hidden" name="id" value="{{$id}}">
                    @endif

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="control-label">{{__('language.Decision')}}</label><span class="text-danger"> *</span>
                                <select class="form-control" type="text" id="decision" name="decision">
                                    <option value="">Select Decision</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 hidden" id="reason_div">
                            <div class="form-group">
                                <label for="reason">Reason <span class="text-danger"> *</span></label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter reason here"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <button type="submit" class="btn btn-primary mr-1" data-toggle="tooltip" data-original-title="Submit">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="check-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Submit')}}</span>
                        </button>
                        <button type="reset" onclick="window.location.href='@if(isset($locale)){{url($locale.'/payroll-management')}} @else {{url('en/payroll-management')}} @endif'" class="btn btn-outline-warning waves-effect">
                            <span class=" d-lg-none d-md-none d-sm-none"><i data-feather="x-circle"></i></span>
                            <span class="d-none d-lg-inline d-md-inline d-sm-inline">{{__('language.Cancel')}}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-payroll-decision.js'))}}"></script>
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>

    <script>
        $("#decision").on("change", function() {
            if ($(this).val() == 'rejected') {
                $("#reason_div").removeClass('hidden');
            } else {
                $("#reason_div").addClass('hidden');
            }
        });
    </script>
@endsection
@stop