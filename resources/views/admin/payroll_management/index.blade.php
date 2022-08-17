@extends('layouts/contentLayoutMaster')
@section('title','Payrolls')

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
    <div class="row" >
        <div class="col-lg-12">
            <div class="card __web-inspector-hide-shortcut__">
                <h3 class="card-header">Filter</h3>

                <form action="@if(isset($locale)){{route('pay-roll.create', [$locale])}} @else {{route('pay-roll.create', ['en'])}} @endif" method="get">
                    <div class="row align-items-center mx-50 row pt-0 pb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="custom-select" onchange="getFilteredData();" id="employee_id" name="employee_id">
                                    <option selected="" value="all">All Employee</option>
                                    @foreach ($employees as $employee)
                                        @if(!$employee->isAdmin())
                                            <option value="{{$employee->id}}">{{$employee->full_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="custom-select" onchange="getFilteredData(value);" id="pay_schedule" name="pay_schedule">
                                    <option value="all">All Pay Schedules</option>
                                    @foreach($paySchedules as $paySchedule)
                                        <option value="{{$paySchedule->id}}">{{$paySchedule->name}}</option>
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
                            <form action="@if(isset($locale)) {{route('payroll-management.decision.create',['en', 'multiple'])}} @else {{route('payroll-management.decision.create',['en', 'multiple'])}} @endif" method="post">
                                <input type="hidden" name="ids" id="ids" value="">
                                @method('GET')
                                @csrf
                                <button type="submit" class="btn btn-primary waves-effect waves-light hidden" tabindex="0" aria-controls="DataTables_Table_0" type="button" id="decision-button" aria-haspopup="true" aria-expanded="false">
                                    <span>
                                        <i data-feather='user-check'></i> Decision
                                    </span>
                                </button>
                            </form>
                        </div>
                        <div class="dt-buttons flex-wrap d-inline-flex" id="exportBtn">
                            <button onclick="exportFunction()" class="btn buttons-collection btn-outline-secondary" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true" aria-expanded="false">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share font-small-4 mr-50"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                                    Export CSV
                                </span>
                            </button>
                        </div>
                        <div class="row col-12 justify-content-end m-0 p-0 div"></div>
                    </div> <!--end card-header-->
                    <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                        <table id="payroll-table" class="dt-simple-header table table-sm dataTable dtr-column">
                            <thead class="thead-light" id="exportColumns">
                                <tr class="text-nowrap text-center">
                                    <th id="checkbox"> 
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="all_checkbox">
                                            <label class="custom-control-label" for="all_checkbox"></label>
                                        </div>
                                    </th>
                                    <th> {{__('language.Name')}}</th>
                                    <th> {{__('language.Mobile Number')}}</th>
                                    <th id="month"> {{__('language.Month')}}</th>
                                    <th> {{__('language.Basic Salary')}}</th>
                                    <th> {{__('language.Housing Allowance')}}</th>
                                    <th> {{__('language.Traveling Expanse')}}</th>
                                    <th> {{__('language.Income Tax')}}</th>
                                    <th> {{__('language.Bonus')}}</th>
                                    <th id="absents"> {{__('language.Absents')}}</th>
                                    <th> {{__('language.Deduction')}}</th>
                                    <th> {{__('language.Custom Deduction')}}</th>
                                    <th> {{__('language.Net Payable')}}</th>
                                    <th> {{__('language.Status')}}</th>
                                    <th> {{__('language.Reason')}}</th>
                                    <th id="actions"> {{__('language.Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count = 1; @endphp
                                @foreach($payrolls as $key => $payroll)
                                    <tr class="text-nowrap text-center">
                                        <td class="checkboxValue">
                                            @if($payroll->status == 'pending' || $payroll->status == '')
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input checkboxes" id="customCheck{{$count}}" value="{{$payroll->id}}" onclick="checkboxClick();">
                                                    <label class="custom-control-label" for="customCheck{{$count}}"></label>
                                                </div>
                                                @php $count = $count+1; @endphp
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="nameValue">{{$payroll->employee->firstname}} {{$payroll->employee->lastname}}</td>
                                        <td class="numberValue">{{$payroll->employee->contact_no}}</td>
                                        <td class="monthValue">
                                            {{\Carbon\Carbon::parse('01-'.$payroll->month_year)->format('F-Y')}}
                                        </td>
                                        <td class="basicSalaryValue">{{$payroll->basic_salary}}</td>
                                        <td class="housingAllowanceValue">{{$payroll->home_allowance}}</td>
                                        <td class="travelingExpanseValue">{{$payroll->travel_expanse}}</td>
                                        <td class="incomeTaxValue">{{$payroll->income_tax}}</td>
                                        <td class="bonusValue">{{$payroll->bonus}}</td>
                                        <td class="absentsValue">{{$payroll->absent_count}}</td>
                                        <td class="deductionValue">{{$payroll->deduction}}</td>
                                        <td class="customDeductionValue">{{$payroll->custom_deduction}}</td>
                                        <td class="netPayableValue">{{$payroll->net_payable}}</td>
                                        <td class="statusValue">
                                            @if($payroll->status == 'approved')
                                                <div class="badge badge-success">Approved</div>
                                            @endif
                                            @if($payroll->status == 'rejected')
                                                <div class="badge badge-danger">Rejected</div>
                                            @endif
                                            @if($payroll->status == 'pending' || $payroll->status == '')
                                                <div class="badge badge-secondary">Pending</div>
                                            @endif
                                        </td>
                                        <td class="reasonValue">
                                            @if($payroll->reason != '')
                                                <div class="hidden reason_text">{{$payroll->reason}}</div>
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#reason{{ $payroll->id }}"><i data-toggle="tooltip"  data-original-title="View Reason" data-feather="eye"></i> </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-nowrap actionsValue">
                                            @if($payroll->status == 'pending' || $payroll->status == '')
                                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="@if(isset($locale)){{route('payroll-management.decision.create', [$locale, $payroll->id])}} @else {{route('payroll-management.decision.create', ['en', $payroll->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Submit Evaluation Decision">
                                                    <i data-feather='user-check'></i>
                                                </a>
                                            @endif

                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $payroll->id }}" data-original-title="Delete"> <i data-feather="trash-2"></i> </a>
                                        </td>
                                    </tr>
                                        
                                    <div class="modal fade" id="confirm-delete{{ $payroll->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="@if(isset($locale)){{url($locale.'/payroll-delete',$payroll->id)}} @else {{url('en/payroll-delete',$payroll->id)}} @endif" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5>{{__('Delete Payroll')}}</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Are you sure you want to delete "{{\Carbon\Carbon::parse('01-'.$payroll->month_year)->format('F-Y')}}" payroll of {{$payroll->employee->firstname}} {{$payroll->employee->lastname}} ?</h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                        <button type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal modal-slide-in fade" id="reason{{ $payroll->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content pt-0">
                                                <div class="modal-header">
                                                    Reason
                                                </div>
                                                <div class="modal-body mt-1 mb-1">
                                                    <div class="form-group">
                                                        <textarea class="form-control" name="reason" rows="3" placeholder="Enter reason here" readonly>{{$payroll->reason}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!--end card-datatable-->
                </div> <!--end card-body-->
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
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/form-payroll-management-script.js'))}}"></script>
    <script src="{{ asset('js/scripts/payroll-management-page-filter-script.js') }}"></script>
    <script src="{{ asset(mix('js/scripts/export-employee-payroll-script.js'))}}"></script>
@endsection
@stop