@extends('layouts/contentLayoutMaster')
@section('title','Benefit Plan')

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
                <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex pr-1">
                    <div class="btn-group dropleft">
                        <a class="btn btn-primary dropdown-toggle"
                           type="button"
                           id="dropdownMenuButton"
                           data-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false"
                           href="@if(isset($locale)){{url($locale.'/asset-types/create')}} @else {{url('en/asset-types/create')}} @endif"
                           class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
                            {{__('language.Add')}} {{__('language.New')}} {{__('language.Plan')}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/health')}}@else{{url('en/benefit-plan/create/health')}}@endif">Health</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/dental')}}@else{{url('en/benefit-plan/create/dental')}}@endif">Dental</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/vision')}}@else{{url('en/benefit-plan/create/vision')}}@endif">Vision</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/supplemental_health')}}@else{{url('en/benefit-plan/create')}}@endif">Supplemental
                                Health</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/retirement')}}@else{{url('en/benefit-plan/create/retirement')}}@endif">Retirement</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/reimbursement')}}@else{{url('en/benefit-plan/create/reimbursement')}}@endif">Reimbursement</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/health_savings_account')}}@else{{url('en/benefit-plan/create/health_savings_account')}}@endif">Health
                                Savings Account</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/flex_spending_account')}}@else{{url('en/benefit-plan/create/flex_spending_account')}}@endif">Flex
                                Spending Account</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/life_insurance')}}@else{{url('en/benefit-plan/create/life_insurance')}}@endif">Life
                                Insurance</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/disability')}}@else{{url('en/benefit-plan/create/disability')}}@endif">Disability</a>
                            <a class="dropdown-item"
                               href="@if(isset($locale)){{url($locale.'/benefit-plan/create/other')}}@else{{url('en/benefit-plan/create/other')}}@endif">Other</a>
                        </div>
                    </div>
                </div> <!--end card-header-->
            </div> <!--end card-->
            <section id="row-grouping-datatable">
                <div class="card-datatable p-1">
                    <table  id="benefit-plan-datatable" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{__('language.Plan')}} {{__('language.Name')}}</th>
                                <th>{{__('language.Benefit')}} {{__('language.Groups')}}</th>
                                <th>{{__('language.Coverage')}}</th>
                                <th>{{__('language.type')}}</th>
                                <th>{{__('language.Actions')}}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($benefitPlanType as $planType)
                                @foreach ($planType->plans as $plan)
                                    <tr class="text-nowrap">
                                        <td>
                                            <a href="@if(isset($locale)){{url($locale.'/benefit-plan/edit/'.$planType->name.'/'.$plan->id)}}@else{{url('en/benefit/benefit-plan/edit/'.$planType->name.'/'.$plan->id)}}@endif">{{$plan->name}}</a>
                                            <br>
                                            @if($plan->start_date > \Carbon\Carbon::now()->toDateString())
{{--                                                if current date is less than start date--}}
                                                Inactive Plan <br> Ends {{$plan->end_date}}
                                            @elseif($plan->end_date < \Carbon\Carbon::now()->toDateString())
{{--                                                else if current date is greater than end date--}}
                                                Inactive Plan <br> Ended {{$plan->end_date}}
                                            @else
{{--                                                else show when plan will end--}}
                                                Ends {{$plan->end_date}}
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($plan->benefitGroup as $group)
                                                {{$group->name}}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($plan->planCoverages as $coverage)
                                                @if ($coverage->coverage_name == 'Employee')
                                                    Employee<br>
                                                @elseif($coverage->coverage_name == 'Employee Spouse')
                                                    Employee Spouse<br>
                                                @elseif($coverage->coverage_name == 'Employee Child')
                                                    Employee Child <br>
                                                @elseif($coverage->coverage_name == 'Employee + Children')
                                                    Employee + Children <br>
                                                @elseif($coverage->coverage_name == 'Two Party')
                                                    Two Party <br>
                                                @elseif($coverage->coverage_name == 'Family Only')
                                                    Family Only <br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td><i class="{{$planType->icon}}"></i> {{$planType->name}}</td>
                                        <td class="nowrap-text">
                                            <a class="btn btn-default" title="Duplicate Plan"
                                               href="@if(isset($locale)){{url($locale.'/benefit-plan/duplicateBenefitPlan',$plan->id)}} @else {{url('en/benefit-plan/duplicateBenefitPlan',$plan->id)}} @endif">
                                                <i data-feather='copy'></i>
                                            </a>
                                            <a class="text-dark" data-toggle="tooltip" data-placement="top" title=""
                                               href="@if(isset($locale)){{url($locale.'/benefit-plan/edit/'.$planType->name.'/'.$plan->id)}}
                                               @else{{url('en/benefit-plan/edit/'.$planType->name.'/'.$plan->id)}}@endif">
                                                <i data-feather="edit-2" class="mr-40"> </i></a>
                                            <a class="btn btn-default" data-toggle="modal" title="Deny"
                                               data-target="#confirm-delete{{ $plan->id }}" data-original-title="del">
                                                <i data-feather='trash-2'></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="confirm-delete{{ $plan->id }}" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <form action="@if(isset($locale)){{url($locale.'/benefit-plan',$plan->id)}} @else {{url('en/benefit/benefit-plan',$plan->id)}} @endif"
                                                      method="POST">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    {{ csrf_field() }}
                                                    <div class="modal-header">
                                                        <h5>Delete Benifit Plan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>{{__('language.Are you sure that you want to permanently delete this plan and unenroll employees?')}} </h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                        <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div> <!--end card-datatable-->
            </section>
        </div> <!--end col-lg-12-->
    </div> <!--end row-->
</div>

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
@endsection
@section('page-script')
    <script>
        initializeTable();

        function initializeTable() {
            var groupColumn = 3;
            var table = $('#benefit-plan-datatable').DataTable({
                "columnDefs": [
                    { "visible": false, "targets": groupColumn }
                ],
                "order": [[ groupColumn, 'asc' ]],
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                            );

                            last = group;
                        }
                    } );
                }
            } );

            // Order by the grouping
            $('#benefit-plan-datatable tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                    table.order( [ groupColumn, 'desc' ] ).draw();
                }
                else {
                    table.order( [ groupColumn, 'asc' ] ).draw();
                }
            } );
        }
    </script>
    {{-- Page js files --}}
    {{-- <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script> --}}
    {{-- <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script> --}}
@endsection
@endsection