@extends('layouts/contentLayoutMaster')
@section('title','Benefit Groups')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}">
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
                        <a class="btn btn-primary"
                           onclick="window.location.href='@if(isset($locale)){{url($locale.'/benefitgroup/create')}} @else {{url('en/benefitgroup/create')}} @endif'"
                           role="button"
                           id="addBenefitGroup">
                            <i data-feather='plus-circle'></i> &nbsp {{__('language.Add Benefit Groups')}}
                        </a>
                    </div> <!--end card-header-->
                </div> <!--end card-->
                <section id="row-grouping-datatable">
                    <div class="card-datatable pl-1 pr-1 table-responsive">
                        <table class="dt-benefit-group table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($benefitGroups as $benefitgroup)
                                    <tr class="bg-light">
                                        <td class="px-1 pt-1 ">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-users">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            {{$benefitgroup->name}} &nbsp;
                                            <span class="text-muted">
                                        {{$benefitgroup->benefitGroupEmployee->count('benefit_group_id')}} Employees</span>
                                        </td>
                                        <td class="px-1 pt-1 ">
                                            <div class="float-right btn-group-justified " style="display: flex">

                                                <a class="mr-2 text-dark " title="Duplicate Benefit Group"
                                                   href="@if(isset($locale)){{url($locale.'/benefitgroup/duplicate',$benefitgroup->id)}} @else {{url('en/benefitgroup/duplicate',$benefitgroup->id)}} @endif">
                                                    <i data-feather='copy'></i>
                                                </a>

                                                <a class="mr-2 text-dark " value="{{$benefitgroup->id}}"
                                                   href=" @if(isset($locale)){{url($locale.'/benefitgroup/'.$benefitgroup->id.'/edit')}} @else
                                                   {{url('en/benefitgroup/'.$benefitgroup->id.'/edit')}} @endif"
                                                   title="Edit Benefit Group">
                                                    <i data-feather='edit-2'></i>
                                                </a>

                                                <a class="mr-2 text-dark " data-toggle="modal"
                                                   data-target="#confirm-delete{{ $benefitgroup->id }}"
                                                   data-original-title="del" title="Delete Benefit Group">
                                                    <i data-feather='trash-2'></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                @php
                                    $firstElement = true;
                                @endphp
                                @foreach ($benefitgroup->benefitPlan as $plan)
                                    @if($firstElement == true)
                                        <tr>
                                            <td>
                                                <a class=""
                                                   href='@if(isset($locale)){{url($locale.'/benefitgroupplan/'.$benefitgroup->id.'/edit')}} @else {{url('en/benefitgroupplan/'.$benefitgroup->id.'/edit')}} @endif'>
                                                    <i data-feather='plus-circle'></i>
                                                    {{__('language.Add Existing Plan')}}</a>
                                            </td>
                                            <td></td>
                                        </tr>
                                        @php
                                            $firstElement = false;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td>
                                            {{$plan->name}}
                                        </td>
                                        <td>
                                            <div class="float-right btn-group-justified">
                                                <a class="mr-2 text-dark" data-toggle="modal"
                                                   title="Edit Plan for this Group"
                                                   onClick="populateGroupPlan({{$plan->id}},{{$benefitgroup->id}})">
                                                    <i data-feather='edit-2'></i>
                                                </a>
                                                <a class="mr-2 text-dark" data-toggle="modal"
                                                   data-target="#deleteGroupPlan{{ $plan->groupPlan->id }}"
                                                   title="Remove Plan from this Group">
                                                    <i data-feather='trash-2'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    {{--                                TODO: Delete Modal for Benefit Group Plans--}}
                                    <div class="modal fade" id="deleteGroupPlan{{ $plan->groupPlan->id }}" tabindex="-1"
                                         role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="@if(isset($locale)){{url($locale.'/benefitgroupplan',$plan->groupPlan->id)}} @else {{url('en/benefitgroupplan',$plan->groupPlan->id)}} @endif" method="post">
                                                    @method('DELETE')
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="groupPlanId" value="{{$plan->groupPlan->id}}">
                                                    <div class="modal-header">
                                                        <h5>Delete Benifit Group</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>{{__('language.Are you sure that you want to permanently delete this group plan and unenroll employees?')}}</h5>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                        <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal modal-slide-in fade" id="editGroupPlan" aria-labelledby="modelTitleId"
                                         aria-hidden="true">
                                        <div class="modal-dialog sidebar-lg" role="document">
                                            <div class="modal-content p-0">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"> Edit Benefit plan</h5>
                                                    {{--                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                        <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                    </button>--}}
                                                </div>
                                                <form action="/updateplan" method="post">
                                                    {{ csrf_field() }}
                                                    <div class="modal-header">
                                                        <h5>{{__('language.Please Adjust Benefit Plan Settings ')}}
                                                        </h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <br>
                                                        <div id="add-plan">
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                        <button type="submit" class="btn btn-primary">{{__('language.Update Benefit Plan')}} </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                Delete Modal for Benefit Group Plans Ends--}}
                                    <div class="modal fade" id="confirm-delete{{ $benefitgroup->id }}" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="@if(isset($locale)){{url($locale.'/benefitgroup',$benefitgroup->id)}} @else {{url('en/benefitgroup',$benefitgroup->id)}} @endif"
                                                      method="post">
                                                    @method('DELETE')
                                                    {{ csrf_field() }}
                                                    @if ($benefitgroup->benefitGroupEmployee->count('benefit_group_id') == 0)
                                                        <div class="modal-header">
                                                            <h5>Delete Benifit Group</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5>{{__('language.Are you sure that you want to permanently delete this group and unenroll employees?')}}
                                                            </h5>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button  type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                        </div>
                                                    @else
                                                        <div class="modal-header">
                                                            <h5>Can' Delete Yet</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5>{{__("language.You've got")}}
                                                                {{$benefitgroup->benefitGroupEmployee->count('benefit_group_id')}}
                                                                {{__("employee in this benefit group.")}}
                                                            </h5>
                                                            <p>{{__("language.Don't leave them hanging, move them to another group before deleting this.")}}
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">{{__("language.I'll Fix It")}}</button>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    {{--                        <table class="table dt-simple-header datatable-basic">--}}
                    {{--                            @foreach ($benefitGroups as $benefitgroup)--}}
                    {{--                                <thead class="thead-light">--}}
                    {{--                                <tbody>--}}
                    {{--                                <tr>--}}
                    {{--                                    <th class="px-1 pt-1 ">--}}
                    {{--                                        <svg xmlns="http://www.w3.org/2000/svg"--}}
                    {{--                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"--}}
                    {{--                                             stroke-linecap="round" stroke-linejoin="round"--}}
                    {{--                                             class="feather feather-users">--}}
                    {{--                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>--}}
                    {{--                                            <circle cx="9" cy="7" r="4"></circle>--}}
                    {{--                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>--}}
                    {{--                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>--}}
                    {{--                                        </svg>--}}
                    {{--                                        {{$benefitgroup->name}} &nbsp;--}}
                    {{--                                        <span class="text-muted">--}}
                    {{--                                    {{$benefitgroup->benefitGroupEmployee->count('benefit_group_id')}} Employees</span>--}}
                    {{--                                    </th>--}}
                    {{--                                    <th class="px-1 pt-1 ">--}}
                    {{--                                        <div class="float-right btn-group-justified " style="display: flex">--}}

                    {{--                                                <a class="mr-2 text-dark " title="Duplicate Benefit Group"--}}
                    {{--                                                   href="@if(isset($locale)){{url($locale.'/benefitgroup/duplicate',$benefitgroup->id)}} @else {{url('en/benefitgroup/duplicate',$benefitgroup->id)}} @endif">--}}
                    {{--                                                    <i data-feather='copy'></i>--}}
                    {{--                                                </a>--}}

                    {{--                                                <a class="mr-2 text-dark " value="{{$benefitgroup->id}}"--}}
                    {{--                                                   href=" @if(isset($locale)){{url($locale.'/benefitgroup/'.$benefitgroup->id.'/edit')}} @else--}}
                    {{--                                                   {{url('en/benefitgroup/'.$benefitgroup->id.'/edit')}} @endif"--}}
                    {{--                                                   title="Edit Benefit Group">--}}
                    {{--                                                    <i data-feather='edit-2'></i>--}}
                    {{--                                                </a>--}}

                    {{--                                                <a class="mr-2 text-dark " data-toggle="modal"--}}
                    {{--                                                   data-target="#confirm-delete{{ $benefitgroup->id }}"--}}
                    {{--                                                   data-original-title="del" title="Delete Benefit Group">--}}
                    {{--                                                    <i data-feather='trash-2'></i>--}}
                    {{--                                                </a>--}}

                    {{--                                        </div>--}}
                    {{--                                    </th>--}}
                    {{--                                </tr>--}}
                    {{--                                </thead>--}}

                    {{--                                @foreach ($benefitgroup->benefitPlan as $plan)--}}
                    {{--                                    <tr>--}}
                    {{--                                        <td>--}}
                    {{--                                            {{$plan->name}}--}}
                    {{--                                        </td>--}}
                    {{--                                        <td>--}}
                    {{--                                            <div class="float-right btn-group-justified">--}}
                    {{--                                                <a class="mr-2 text-dark" data-toggle="modal"--}}
                    {{--                                                   title="Edit Plan for this Group"--}}
                    {{--                                                   onClick="populateGroupPlan({{$plan->id}},{{$benefitgroup->id}})">--}}
                    {{--                                                    <i data-feather='edit-2'></i>--}}
                    {{--                                                </a>--}}
                    {{--                                                <a class="mr-2 text-dark" data-toggle="modal"--}}
                    {{--                                                   data-target="#deleteGroupPlan{{ $plan->groupPlan->id }}"--}}
                    {{--                                                   title="Remove Plan from this Group">--}}
                    {{--                                                    <i data-feather='trash-2'></i>--}}
                    {{--                                                </a>--}}
                    {{--                                            </div>--}}
                    {{--                                        </td>--}}
                    {{--                                    </tr>--}}
                    {{--                                    TODO: Delete Modal for Benefit Group Plans --}}
                    {{--                                    <div class="modal fade" id="deleteGroupPlan{{ $plan->groupPlan->id }}" tabindex="-1"--}}
                    {{--                                         role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">--}}
                    {{--                                        <div class="modal-dialog">--}}
                    {{--                                            <div class="modal-content">--}}
                    {{--                                                <form action="@if(isset($locale)){{url($locale.'/benefitgroupplan',$plan->groupPlan->id)}} @else {{url('en/benefitgroupplan',$plan->groupPlan->id)}} @endif"--}}
                    {{--                                                      method="post">--}}
                    {{--                                                    @method('DELETE')--}}
                    {{--                                                    {{ csrf_field() }}--}}
                    {{--                                                    <input type="hidden" name="groupPlanId"--}}
                    {{--                                                           value="{{$plan->groupPlan->id}}">--}}
                    {{--                                                    <div class="modal-header">--}}
                    {{--                                                        <button type="button" class="close" data-dismiss="modal"--}}
                    {{--                                                                aria-label="Close">--}}
                    {{--                                                            <span aria-hidden="true">&times;</span>--}}
                    {{--                                                        </button>--}}
                    {{--                                                    </div>--}}
                    {{--                                                    <div class="modal-body">--}}
                    {{--                                                        <h5>{{__('language.Are you sure that you want to permanently delete this group plan and unenroll employees?')}}--}}
                    {{--                                                        </h5>--}}
                    {{--                                                    </div>--}}
                    {{--                                                    <div class="modal-footer">--}}
                    {{--                                                        <button type="submit"--}}
                    {{--                                                                class="btn btn-primary btn-cancel">{{__('language.Delete')}}</button>--}}
                    {{--                                                    </div>--}}
                    {{--                                                </form>--}}
                    {{--                                            </div>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                     Delete Modal for Benefit Group Plans Ends --}}
                    {{--                                @endforeach--}}
                    {{--                                <tr>--}}
                    {{--                                    <td colspan="2">--}}
                    {{--                                        <a class=""--}}
                    {{--                                           href='@if(isset($locale)){{url($locale.'/benefitgroupplan/'.$benefitgroup->id.'/edit')}} @else {{url('en/benefitgroupplan/'.$benefitgroup->id.'/edit')}} @endif'>--}}
                    {{--                                            <i data-feather='plus-circle'></i>--}}
                    {{--                                            {{__('language.Add Existing Plan')}}</a>--}}
                    {{--                                    </td>--}}
                    {{--                                </tr>--}}

                    {{--                                </tbody>--}}

                    {{--                                --}}{{--TODO: Delete Modal for Benefit Group --}}

                    {{--                                --}}{{-- Delete Modal for Benefit Group Ends --}}
                    {{--                            @endforeach--}}
                    {{--                        </table>--}}

                    <!-- Modal -->

                    </div>
                </section>
            </div>
            <!--end::Portlet-->
        </div>
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
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script>
        var table = $('.dt-benefit-group').DataTable({
            "ordering": false
        } );
        table.rowReorder.disable();
        function populateGroupPlan(planId, groupId) {
            $.ajax({
                type: "get",
                url: "/getGroupPlan",
                data: {
                    'planId': planId,
                    'groupId': groupId
                },
                success: function (benefitPlanArray) {
                    let benefitPlan = benefitPlanArray[0];
                    console.log(benefitPlan);

                    displayPlanDetail(benefitPlan);
                }
            });
        }

        let levelCount = 0;
        let coverageCount = 0;

        function displayPlanDetail(benefitPlan) {
            $('#editGroupPlan').modal('show');
            let group_plan_cost = benefitPlan.group_plan_cost;
            let eligibility = " eligibilityType";
            let deduction_exception = " deduction_exception";
            let coverage_detail = " coverage_detail";
            let plan_id = " plan_id";
            $('#add-plan').empty();
            $('#add-plan').append(
                '<div class="level-section col-md-12" level="' + levelCount + '" plan-id="' + benefitPlan.id + '">\n' +
                '<label class="control-label">When will this Benefit Group become eligible for the <span class="text-success">' + benefitPlan.group_plans.name + '</span> ?</label>\n' +
                '<input type="hidden" name="group_plan_id" value="' + benefitPlan.id + '" aria-describedby="helpId" placeholder="">\n' +
                '<input type="hidden" name="group_id" value="' + benefitPlan.group_id + '" aria-describedby="helpId" placeholder="">\n' +
                '<div class="form-group eligibilityType' + levelCount + ' row pl-1">\n' +
                '<select class="col-md-12 form-control eligibilityType_select" name=' + eligibility + ' level="' + levelCount + '">\n' +
                '    <option value="manual" selected>When it is marked manually</option>\n' +
                '    <option value="hire_date" >Immediately upon hire</option>\n' +
                '    <option value="waiting_period" >After a waiting period</option>\n' +
                '    <option value="month_after_waiting_period" >First of the month following a waiting period</option>\n' +
                '</select>\n' +
                '</div>\n' +
                '<div class="form-group col-md-12 deductionExceptionType' + levelCount + '">\n' +
                '    <label class="control-lable">Does this benefit deduction happen every paycheck?</label><br>\n' +
                '    <input onclick="removeDeductionExceptionType(' + levelCount + ')" type="radio" name="' + deduction_exception + '" value="yes" checked level="' + levelCount + '"> Yes (Most common)<br>\n' +
                '    <input onclick="appendDeductionExceptionType(' + levelCount + ')" type="radio" name="' + deduction_exception + '" value="no" level="' + levelCount + '"> No, it skips some paychecks\n' +
                '</div>\n' +
                '<table class="table ">\n' +
                '    <thead>\n' +
                '        <tr>\n' +
                '            <th>Plan ID</th>\n' +
                '            <th>Coverage ID</th>\n' +
                '            <th>Coverage Level</th>\n' +
                '            <th>Employee Cost</th>\n' +
                '            <th>Company Cost</th>\n' +
                '            <th>Total Cost</th>\n' +
                '        </tr>\n' +
                '    </thead>\n' +
                '    <tbody id ="coverage-detail' + levelCount + '" >\n' +
                '    </tbody>\n' +
                '</table>\n' +
                '<hr>\n' +
                '</div>'
            );
            if ("Standard" == benefitPlan.group_plans.plan_cost_rate) {
                $.each(group_plan_cost, function (index, value) {
                    let selector = '#coverage-detail' + levelCount;
                    let group_plan_cost_id = " plan[" + coverageCount + "][group_plan_cost_id]]";
                    let coverage_id = " plan[" + coverageCount + "][coverage_id]]";
                    let employee_cost = " plan[" + coverageCount + "][employee_cost]]";
                    let company_cost = " plan[" + coverageCount + "][company_cost]]";
                    '<input type="hidden"  name="' + group_plan_cost_id + '"  value="' + value.id + '">\n' +
                    $(selector).append(
                        '<tr>\n' +
                        '   <td><input type="hidden"  name="' + group_plan_cost_id + '"  value="' + value.id + '"><input size="2" style="border:0px;" type="text" name=' + plan_id + ' value="' + benefitPlan.group_plans.id + '" readonly ></td>\n' +
                        '   <td><input size="2" style="border:0px;" type="text" name=' + coverage_id + ' value="' + value.coverage_id + '" readonly></td>\n' +
                        '   <td>' + value.plan_cost_coverage.coverage_name + '</td>\n' +
                        '   <td><input size="2" type="text" name=' + employee_cost + ' value="' + value.employee_cost + '"></td>\n' +
                        '   <td><input size="2" type="text" name=' + company_cost + ' value="' + value.company_cost + '"></td>\n' +
                        '   <td>' + (parseInt(value.employee_cost) + parseInt(value.company_cost)) + '</td>\n' +
                        '</tr>'
                    );
                    coverageCount++;
                });
            } else {
                let selector = '.table ';
                $(selector).remove();
            }
        }

        $(document).on('change', '.eligibilityType_select', function () {
            let level = $(this).attr('level');
            let waitingPeriodType_value = $("option:selected", this).val();
            let waitingPeriodType = "waitingPeriodType";
            if (waitingPeriodType_value == 'manual') {
                $('.waitingPeriodType_value' + level).remove();
                $('.eligibilityType_select' + level).remove();
            }
            if (waitingPeriodType_value == 'hire_date') {
                $('.waitingPeriodType_value' + level).remove();
                $('.eligibilityType_select' + level).remove();
            }
            if (waitingPeriodType_value == 'waiting_period') {
                $('.waitingPeriodType_value' + level).remove();
                $('.eligibilityType_select' + level).remove();
                $('.eligibilityType' + level).append(
                    '<div class="col-md-6 waitingPeriodType_value' + level + '">\n' +
                    '    <input type="text" name="waiting_period" class="form-control">\n' +
                    '</div>\n' +
                    '<select class="col-md-6 form-control eligibilityType_select' + level + '" name=' + waitingPeriodType + ' id="eligibility' + level + '"  level="' + level + '">\n' +
                    '    <option value="days" selected>Days</option>\n' +
                    '    <option value="week" >Weeks</option>\n' +
                    '    <option value="months" >Months</option>\n' +
                    '    <option value="years" >Years</option>\n' +
                    '</select>'
                );
            }
            if (waitingPeriodType_value == 'month_after_waiting_period') {
                $('.waitingPeriodType_value' + level).remove();
                $('.eligibilityType_select' + level).remove();
                $('.eligibilityType' + level).append(
                    '<div class="col-md-2 waitingPeriodType_value' + level + '">\n' +
                    '    <input type="text" name="waiting_period" class="form-control">\n' +
                    '</div>\n' +
                    '<select class="col-md-3 form-control eligibilityType_select' + level + '" name=' + waitingPeriodType + ' id="eligibility' + level + '"  level="' + level + '">\n' +
                    '    <option value="days" selected>Days</option>\n' +
                    '    <option value="week" >Weeks</option>\n' +
                    '    <option value="months" >Months</option>\n' +
                    '    <option value="years" >Years</option>\n' +
                    '</select>'
                );
            }
        });

        function appendDeductionExceptionType(level) {
            $('#deductionException' + level).remove();
            let deductionExceptionType = "deductionExceptionType";
            $('.deductionExceptionType' + level).after(
                '<select class="col-md-6 form-control" id="deductionException' + level + '" name=' + deductionExceptionType + ' level="' + level + '">\n' +
                '    <option value="monthly" selected>Monthly</option>\n' +
                '    <option value="quarterly" >Quarterly</option>\n' +
                '    <option value="twice_a_year" >Twice a yearly</option>\n' +
                '    <option value="years" >Yearly</option>\n' +
                '</select>'
            );
        }

        function removeDeductionExceptionType(level) {
            $('#deductionException' + level).remove();
        }
    </script>
@endsection

@stop