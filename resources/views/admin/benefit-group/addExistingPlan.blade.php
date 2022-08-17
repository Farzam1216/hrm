@extends('layouts/contentLayoutMaster')
@section('title','Add Existing Plan')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4>Add Plan in {{$benefitGroup->name}}</h4><br>
                    <form action="/addavailableplan" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <input type="hidden" name="group_id" value="{{$benefitGroup->id}}">
                            <div class="col-md-4">
                                <select class="form-control" id="availablePlans" name="plan_id">
                                    <option selected disabled>Select Benefit Plan</option>
                                    @foreach($availableBenefitPlans as $plan)
                                        <option value="{{$plan->id}}">{{$plan->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id=add-plan>

                        </div>
                        <button type="submit" class="btn btn-primary mt-2">{{__('language.Add Existing Plan')}}</button>
                        <a type="button" href="@if(isset($locale)){{url($locale.'/benefitgroup')}}
                        @else {{url('en/benefitgroup')}} @endif" class="btn btn-inverse mt-2">{{__('language.Cancel')}}</a>
                    </form>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
    @section('page-script')
        <script>
            $(document).ready(function () {
                // console.log('ready')
                $(document).on('change', '#availablePlans', function (){
                    // $('#availablePlans').change(function () {
                    let planId = $(this).val();
                    console.log(planId);
                    $.ajax({
                        type: "get",
                        url: "/getplan",
                        data: {
                            'id':planId,
                        },
                        success: function (benefitPlanArray) {
                            let benefitPlan = benefitPlanArray[0];
                            displayPlanDetail(benefitPlan);
                        }
                    });

                });
            })
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
                    '<div class="level-section col-md-12" level="' + levelCount + '" plan-id="'+benefitPlan.id+'">\n' +
                    '<label class="control-label ml-1">When will this Benefit Group become eligible for the <span class="text-success">'+benefitPlan.name+'</span> ?</label>\n' +
                    '<input type="hidden" name="group_plan_id" value="'+benefitPlan.id+'" aria-describedby="helpId" placeholder="">\n' +
                    '<div class="form-group eligibilityType'+levelCount+' row">\n' +
                    '<select class="col-md-6 ml-2 form-control eligibilityType_select" name='+eligibility+' level="'+levelCount+'">\n' +
                    '    <option value="manual" selected>When it is marked manually</option>\n' +
                    '    <option value="hire_date" >Immediately upon hire</option>\n' +
                    '    <option value="waiting_period" >After a waiting period</option>\n' +
                    '    <option value="month_after_waiting_period" >First of the month following a waiting period</option>\n' +
                    '</select>\n' +
                    '</div>\n' +
                    '<div class="form-group col-md-8 deductionExceptionType'+levelCount+'">\n' +
                    '    <label class="control-lable">Does this benefit deduction happen every paycheck?</label><br>\n' +
                    '    <input onclick="removeDeductionExceptionType('+levelCount+')" type="radio" name="'+deduction_exception+'" value="yes" checked level="' + levelCount + '"> Yes (Most common)<br>\n' +
                    '    <input onclick="appendDeductionExceptionType('+levelCount+')" type="radio" name="'+deduction_exception+'" value="no" level="' + levelCount + '"> No, it skips some paychecks\n' +
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
                    '    <tbody id ="coverage-detail'+levelCount+'" >\n' +
                    '    </tbody>\n' +
                    '</table>\n' +
                    '<hr>\n' +
                    '</div>'

                );
                if ( "Standard" == benefitPlan.plan_cost_rate) {
                    $.each(benefitPlan.plan_coverages, function (index, value) {
                        let selector = '#coverage-detail'+levelCount;
                        let group_plan_cost_id = " plan[" + coverageCount + "][group_plan_cost_id]]";
                        let coverage_id = " plan[" + coverageCount + "][coverage_id]]";
                        let employee_cost = " plan[" + coverageCount + "][employee_cost]]";
                        let company_cost = " plan[" + coverageCount + "][company_cost]]";
                        '<input type="hidden"  name="'+group_plan_cost_id+'"  value="'+value.id+'">\n' +
                        $(selector).append(
                            '<tr>\n' +
                            '   <td><input size="2" style="border:0px;" type="text" name='+plan_id+' value="'+benefitPlan.id+'" readonly ></td>\n' +
                            '   <td><input size="2" style="border:0px;" type="text" name='+coverage_id+' value="'+value.id+'" readonly></td>\n' +
                            '   <td>'+value.coverage_name+'</td>\n' +
                            '   <td><input size="2" type="text" name='+employee_cost+' value="0"></td>\n' +
                            '   <td><input size="2" type="text" name='+company_cost+' value="'+value.total_cost+'"></td>\n' +
                            '   <td>'+value.total_cost+'</td>\n' +
                            '</tr>'
                        );
                        coverageCount++;
                    });
                }else
                {
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
                        '<div class="col-md-2 waitingPeriodType_value' + level + '">\n' +
                        '    <input type="text" name="waiting_period" class="form-control">\n' +
                        '</div>\n' +
                        '<select class="col-md-3 form-control eligibilityType_select' + level + '" name='+waitingPeriodType+' id="eligibility'+level+'"  level="'+level+'">\n' +
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
                        '<select class="col-md-3 form-control eligibilityType_select' + level + '" name='+waitingPeriodType+' id="eligibility'+level+'"  level="'+level+'">\n' +
                        '    <option value="days" selected>Days</option>\n' +
                        '    <option value="week" >Weeks</option>\n' +
                        '    <option value="months" >Months</option>\n' +
                        '    <option value="years" >Years</option>\n' +
                        '</select>'
                    );
                }
            });
            function appendDeductionExceptionType(level) {
                let deductionExceptionType = "deductionExceptionType";
                $('.deductionExceptionType' + level).after(
                    '<select class="col-md-3 form-control" id="deductionException'+level+'" name='+deductionExceptionType+' level="'+level+'">\n' +
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
