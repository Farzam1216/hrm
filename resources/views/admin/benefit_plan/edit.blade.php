@extends('layouts/contentLayoutMaster')
@section('title','Benefit Plan')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="">
                <!-- Horizontal Wizard -->
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        @if($planType->name == 'Health' || $planType->name == 'Dental' || $planType->name == 'Vision' || $planType->name == 'Supplemental Health')
                            @if (str_contains($planType, '_'))
                                <h4 class="px-2 pt-2">Edit {{strtoupper(str_replace('_',' ',$planType->name))}}
                                    Plan</h4>
                            @else
                                <h4 class="px-2 pt-2">Edit {{strtoupper($planType->name)}} Plan</h4>
                            @endif
                            <div class="bs-stepper-header">
                                {{--                                <div class="stepwizard-step">--}}
                                <div class="step stepwizard-step" data-target="#account-details">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">1</span>
                                        <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 1</span>
                                        <span class="bs-stepper-subtitle">Plan Details</span>
                                      </span>
                                    </button>
                                    {{--                                </div>--}}
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                {{--                                <div class="stepwizard-step">--}}
                                <div class="step stepwizard-step" data-target="#personal-info">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">2</span>
                                        <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Step 2</span>
                                    <span class="bs-stepper-subtitle">Plan Coverage</span>
                                  </span>
                                    </button>
                                    {{--                                </div>--}}
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                {{--                                <div class="stepwizard-step">--}}
                                <div class="step stepwizard-step" data-target="#address-step">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">3</span>
                                        <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 3</span>
                                        <span class="bs-stepper-subtitle">Plan Cost</span>
                                      </span>
                                    </button>
                                    {{--                                </div>--}}
                                </div>
                            </div>

                            <div class="bs-stepper-content">
                                <form role="form" id="submit_form"
                                      action="@if(isset($locale)){{url($locale.'/benefit-plan/'.$id)}}@else{{url('en/benefit/benefit-plan/'.$id)}}@endif"
                                      method="post">
                                    <input name="_method" type="hidden" value="PUT">
                                    {{csrf_field()}}
                                    <div id="account-details" class="content">
                                        <fieldset>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="plan_name">Plan Name</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="text" class="form-control" name="plan_name"
                                                           id="plan_name" value="{{$benefitPlan->name}}"
                                                           placeholder="e.g. SelectHealth - Platinum">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="rate_select">Rate</label><span
                                                            class="text-danger"> *</span>
                                                    <select class="form-control" name="rate_select">
                                                        @foreach($currencies as $currency)
                                                            <option value="{{$currency->code}}"
                                                                    @if($currency->code == $benefitPlan->plan_cost_rate) selected @endif>{{$currency->code}}</option>
                                                        @endforeach
                                                    </select>
                                                    {{--                                                    <input type="text" class="form-control" name="rate_select"--}}
                                                    {{--                                                           value="{{$benefitPlan->plan_cost_rate}} e.g. Age based--}}
                                                    {{--                                                            " style="opacity: 60%" readonly>--}}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <input type="text" name="plan_id" id="plan_id"
                                                       value="{{$planType->id}}" hidden>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label text-right">{{__('language.From')}}</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="date" id="from" name="from"
                                                           value="{{$benefitPlan->start_date}}"
                                                           class="form-control flatpickr-disabled-range"/>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label text-right">{{__('language.To')}}</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="date" id="to" name="to"
                                                           @if(isset($benefitPlan->end_date)) value="{{$benefitPlan->end_date}}"
                                                           @endif class="form-control flatpickr-disabled-range"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label">{{__('language.Description')}}</label>
                                                    <span
                                                            class="text-danger"> *</span>
                                                    <textarea class="form-control" name="description" id="description"
                                                              cols="30" rows="3" onclick="myFunction()"
                                                    >{{$benefitPlan->description}}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="Url">Plan Url</label>
                                                    <input class="form-control" type="text" name="plan_URL"
                                                           id="plan_URL" value="{{$benefitPlan->plan_URL}}">
                                                    <small class="">
                                                        We recommend adding the URL of your Plan's find-a-doctor
                                                        page or another page that you think would be most useful to your
                                                        employees
                                                    </small>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="d-flex justify-content-between">
                                            <a type="button"
                                               href="@if(isset($locale)){{url($locale.'/benefit-plan')}}
                                               @else {{url('en/benefit-plan')}} @endif"
                                               class="btn btn-outline-warning ">{{__('language.Cancel')}}</a>
                                            <a class="btn btn-primary float-right btn-next">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right"
                                                   class="align-middle ml-sm-25 "></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="personal-info" class="content">
                                        <fieldset>
                                            <div class="row pl-2 pr-2 pb-2" id="step-2">
                                                <div class="col-md-12">
                                                    <b>What coverage options are available on this plan?</b>
                                                    <p>Select All that apply<span class="text-danger"> *</span></p>
                                                    <div class="form-check">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck1" value="Employee"
                                                                   value="Employee"
                                                                   @foreach($planCoverages as $planCoverage)
                                                                   @if($planCoverage->coverage_name == "Employee") checked @endif
                                                                    @endforeach>
                                                            <label class="custom-control-label" for="customCheck1">Employee</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck2"
                                                                   value="Employee Spouse"
                                                                   @foreach($planCoverages as $planCoverage)
                                                                   @if($planCoverage->coverage_name == "Employee Spouse") checked @endif
                                                                    @endforeach>
                                                            <label class="custom-control-label" for="customCheck2">Employee
                                                                + Spouse</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck3"
                                                                   value="Employee Child"
                                                                   @foreach($planCoverages as $planCoverage)
                                                                   @if($planCoverage->coverage_name == "Employee Child") checked @endif
                                                                    @endforeach>
                                                            <label class="custom-control-label" for="customCheck3">
                                                                Employee + Child</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck4"
                                                                   value="Employee + Children"
                                                                   @foreach($planCoverages as $planCoverage)
                                                                   @if($planCoverage->coverage_name == "Employee + Children") checked @endif
                                                                    @endforeach>
                                                            <label class="custom-control-label" for="customCheck4">Employee
                                                                + Children
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck5"
                                                                   value="Two Party"
                                                                   @foreach($planCoverages as $planCoverage)
                                                                   @if($planCoverage->coverage_name == "Two Party") checked @endif
                                                                    @endforeach>
                                                            <label class="custom-control-label" for="customCheck5">Two
                                                                Party
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck6"
                                                                   value="Family Only"
                                                                   @foreach($planCoverages as $planCoverage)
                                                                   @if($planCoverage->coverage_name == "Family Only") checked @endif
                                                                    @endforeach>
                                                            <label class="custom-control-label" for="customCheck6">Family
                                                                Only <small> Employee not included</small>
                                                            </label></div>
                                                        <div class="hidden_plan_coverages"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="submit"></div>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-primary btn-prev">
                                                <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-primary btn-next variable_rate_remove"
                                                    id="plan_costs">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right"
                                                   class="align-middle ml-sm-25 ml-0"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="address-step" class="content">
                                        <fieldset>
                                            <div class="col-md-12">
                                                <b>What are the Total Monthly Costs for this plan? (Employee + Company
                                                    cost)</b>
                                                <p>This will help us to calculate the employee cost when you add this
                                                    plan to a
                                                    benefit group.</p>
                                                <div id="plan_cost">

                                                </div>

                                            </div>
                                        </fieldset>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-primary btn-prev">
                                                <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-success btn-submit">Update Plan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @elseif($planType->name == 'Retirement' || $planType->name == 'Reimbursement' || $planType->name == 'Health Savings Account' || $planType->name == 'Flex Spending Account'|| $planType->name == 'Life Insurance'|| $planType->name == 'Disability'|| $planType->name == 'Other')
                            @if (str_contains($planType, '_'))
                                <h4 class="px-2 pt-2">Edit {{strtoupper(str_replace('_',' ',$planType->name))}}
                                    Plan</h4>
                            @else
                                <h4 class="px-2 pt-2">Edit {{strtoupper($planType->name)}} Plan</h4>
                            @endif
                            <div class="bs-stepper-header d-none">
                                <div class="stepwizard-step">
                                    <div class="step" data-target="#account-details">
                                        <button type="button" class="step-trigger">
                                            <span class="bs-stepper-box">1</span>
                                            <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 1</span>
                                        <span class="bs-stepper-subtitle">Plan Details</span>
                                      </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bs-stepper-content">
                                <form role="form" id="submit_form"
                                      action="@if(isset($locale)){{url($locale.'/benefit-plan/'.$id)}}@else{{url('en/benefit/benefit-plan/'.$id)}}@endif"
                                      method="POST">
                                    <input name="_method" type="hidden" value="PUT">
                                    {{csrf_field()}}
                                    <div id="account-details" class="content">
                                        <fieldset>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="plan_name">Plan Name</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="text" class="form-control" name="plan_name"
                                                           id="plan_name" value="{{$benefitPlan->name}}"
                                                           placeholder="e.g. SelectHealth - Platinum">
                                                </div>


                                                @if($planType->name == 'Reimbursement')
                                                    {{--                                                        <div class="row">--}}
                                                    {{--                                                            <br>--}}
                                                    {{--                                                            <p>The company will reimburse up to...</p>--}}
                                                    {{--                                                        </div>--}}


                                                    <div class="row col-md-12" >
                                                        <div class="col-md-12 ">
                                                            <p>The company will reimburse up to...</p>
                                                        </div>
                                                        <div class="form-group col-md-4 ">
                                                            <label for="reimbursement_amount">Amount</label><span
                                                                    class="text-danger"> *</span>
                                                            <input type="number" class="form-control" placeholder="Amount"
                                                                   name="reimbursement_amount" id="reimbursement_amount"
                                                                   value="{{$benefitPlan->reimbursement_amount}}">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="reimbursement_currency">Currency</label><span
                                                                    class="text-danger"> *</span>
                                                            <select class="form-control" name="reimbursement_currency">
                                                                @foreach($currencies as $currency)
                                                                    <option value="{{$currency->code}}"
                                                                            @if($currency->code == $benefitPlan->reimbursement_currency) selected @endif >{{$currency->code}}</option>
                                                                @endforeach
                                                            </select>
                                                            {{--                                                        <input type="text" value="USD" name="reimbursement_currency"--}}
                                                            {{--                                                               class="form-control" readonly>--}}

                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="reimbursement_frequncy">Period</label><span
                                                                    class="text-danger"> *</span>
                                                            <select class="form-control"
                                                                    name="reimbursement_frequncy"
                                                                    id="reimbursement_frequncy">
                                                                <option value="every_pay_period"
                                                                        @if($benefitPlan->reimbursement_frequency == "every_pay_period") selected @endif>
                                                                    Every Pay Period
                                                                </option>
                                                                <option value="every_month"
                                                                        @if($benefitPlan->reimbursement_frequency == "every_month") selected @endif>
                                                                    Every Month
                                                                </option>
                                                                <option value="every_quater"
                                                                        @if($benefitPlan->reimbursement_frequency == "every_quater") selected @endif>
                                                                    Every Quater
                                                                </option>
                                                                <option value="every_year"
                                                                        @if($benefitPlan->reimbursement_frequency == "every_year") selected @endif>
                                                                    Every Year
                                                                </option>
                                                                <option value="one_time"
                                                                        @if($benefitPlan->reimbursement_frequency == "one_time") selected @endif>
                                                                    One Time
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
{{--                                                    <div class="row pl-1">--}}
{{--                                                        <div class="form-group col-md-5">--}}
{{--                                                            <span class="text-danger"> *</span>--}}
{{--                                                            <input type="number" class="form-control"--}}
{{--                                                                   name="reimbursement_amount" id="reimbursement_amount"--}}
{{--                                                                   value="{{$benefitPlan->reimbursement_amount}}">--}}
{{--                                                        </div>--}}
{{--                                                        <div class="form-group col-md-2">--}}
{{--                                                            <select class="form-control mt-2"--}}
{{--                                                                    name="reimbursement_currency">--}}
{{--                                                                @foreach($currencies as $currency)--}}
{{--                                                                    <option value="{{$currency->code}}"--}}
{{--                                                                            @if($currency->code == $benefitPlan->reimbursement_currency) selected @endif>{{$currency->code}}</option>--}}
{{--                                                                @endforeach--}}
{{--                                                            </select>--}}
{{--                                                            --}}{{--                                                            <input type="text" value="USD" name="reimbursement_currency"--}}
{{--                                                            --}}{{--                                                                   class="form-control mt-2" readonly>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="form-group col-md-5">--}}
{{--                                                            <select class="form-control mt-2"--}}
{{--                                                                    name="reimbursement_frequncy"--}}
{{--                                                                    id="reimbursement_frequncy">--}}
{{--                                                                <option value="">-Select-</option>--}}
{{--                                                                <option value="every_pay_period"--}}
{{--                                                                        @if($benefitPlan->reimbursement_frequency == "every_pay_period") selected @endif>--}}
{{--                                                                    Every Pay Period--}}
{{--                                                                </option>--}}
{{--                                                                <option value="every_month"--}}
{{--                                                                        @if($benefitPlan->reimbursement_frequency == "every_month") selected @endif>--}}
{{--                                                                    Every Month--}}
{{--                                                                </option>--}}
{{--                                                                <option value="every_quater"--}}
{{--                                                                        @if($benefitPlan->reimbursement_frequency == "every_quater") selected @endif>--}}
{{--                                                                    Every Quater--}}
{{--                                                                </option>--}}
{{--                                                                <option value="every_year"--}}
{{--                                                                        @if($benefitPlan->reimbursement_frequency == "every_year") selected @endif>--}}
{{--                                                                    Every Year--}}
{{--                                                                </option>--}}
{{--                                                                <option value="one_time"--}}
{{--                                                                        @if($benefitPlan->reimbursement_frequency == "one_time") selected @endif>--}}
{{--                                                                    One Time--}}
{{--                                                                </option>--}}
{{--                                                            </select>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                @elseif( $planType->name == 'Flex Spending Account')
                                                    <div class="form-group col-md-6">
                                                        <label for="rate_select">Rate</label><span class="text-danger"> *</span>
                                                        <select class="form-control" name="rate_select"
                                                                id="rate_select">
                                                            <option value="General Healthcare Purposes"
                                                                    @if($benefitPlan->plan_cost_rate == "General Healthcare Purposes")
                                                                    selected @endif>General Healthcare Purposes <small>(Most
                                                                    Common)</small>
                                                            </option>
                                                            <option value="Dependent Care Purposes"
                                                                    @if($benefitPlan->plan_cost_rate == "Dependent Care Purposes")
                                                                    selected @endif>Dependent Care Purposes
                                                            </option>
                                                        </select>
                                                    </div>
                                                @endif
                                                {{--                                                </div>--}}
                                            </div>
                                            <div class="row">
                                                <input type="text" name="plan_id" id="plan_id"
                                                       value="{{$planType->id}}" hidden>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label text-right">{{__('language.From')}}</label><span
                                                            class="text-danger"> *</span>
                                                    @if($planType->name == 'Disability' || $planType->name == 'Reimbursement' || $planType->name == 'Flex Spending Account')
                                                        <input type="hidden" name="from"
                                                               value="{{$benefitPlan->start_date}}"/>
                                                        <input type="date" id="from" name="from"
                                                               value="{{$benefitPlan->start_date}}"
                                                               class="form-control flatpickr-disabled-range" disabled/>

                                                    @else
                                                        <input type="date" id="from" name="from"
                                                               value="{{$benefitPlan->start_date}}"
                                                               class="form-control flatpickr-disabled-range"/>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label text-right">{{__('language.To')}}</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="date" id="to" name="to"
                                                           @if(isset($benefitPlan->end_date)) value="{{$benefitPlan->end_date}}"
                                                           @endif class="form-control flatpickr-disabled-range"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label">{{__('language.Description')}} <span
                                                                class="text-danger"> *</span></label>
                                                    <textarea class="form-control" name="description" id="description"
                                                              cols="30" rows="3" onclick="myFunction()"
                                                    >{{$benefitPlan->description}}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="Url">Plan Url</label>
                                                    <input class="form-control" type="text" name="plan_URL"
                                                           id="plan_URL" value="{{$benefitPlan->plan_URL}}">
                                                    <small class="">
                                                        We recommend adding the URL of your Plan's find-a-doctor
                                                        page or another page that you think would be most useful to your
                                                        employees
                                                    </small>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a type="button"
                                                   href='@if(isset($locale)){{url($locale.'/benefit-plan')}}
                                                   @else {{url('en/benefit-plan')}} @endif'
                                                   class="btn btn-outline-warning ">{{__('language.Cancel')}}</a>
                                                <button class="btn btn-primary float-right btn-submit ">{{__('language.Update')}} {{__('language.Benefit')}} {{__('language.Plan')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        @endif
                    </div>
                </section>
                <!-- /Horizontal Wizard -->

            </div> <!--end model content-->
        </div>

    </div>
    <!--end::card body-->
    </div>

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>

@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>
    <script>
        const onChange = (sel) => {
            if (sel.value === 'Variable') {
                var hidden = $('.stepwizard-step');
                hidden = hidden[2];
                $(hidden).hide();
                $('.variable_rate_remove').hide();
                var $input = $('<button class="btn btn-success nextBtn pull-right hidden_plan_cost align-middle d-sm-inline-block d-none" style="float: right;"\n' +
                    '                                                type="submit">{{__('language.Update')}} {{__('language.Benefit Plan')}}\n' +
                    '                                        </button>');
                $input.appendTo($(".submit"));
            } else {
                var hidden = $('.stepwizard-step');
                hidden = hidden[2];
                $(hidden).show();
                $('.variable_rate_remove').show();
            }
        }

        function handleSelectedCurrency(ele) {
            $('.selectedCurrency').val($(ele).val());
        }

        $(document).ready(function () {
            $(function () {

                $('div.setup-panel div a.btn-primary').trigger('click');
                var currencies = {!! $currencies !!};
                var planCoverage = {!! $planCoverages !!};
                var planCoveragesName = {!! $planCoveragesName !!};
                var option = '';
                var totalcost = '';
                $("#empty_div").on("click", function () {
                    $("#plan_cost").empty();
                });
                $("#plan_costs").on("click", function () {
                    var checkedIds = $(".chk:checked").map(function () {
                        return this.value;
                    }).toArray();
                    $('#plan_cost').html('');
                    checkedIds.forEach(function (value, i) {
                        // const value = checkedIds[i];
                        // console.log(value, "value");

                        if (planCoveragesName.includes(value)) {
                            for (let j = 0; j < planCoverage.length; j++) {
                                if (planCoverage[j].coverage_name == value) {
                                    totalcost = '<input type="number" name="plan_coverages[' + i + '][total_cost]" class="form-control" value="' + planCoverage[j].total_cost + '">';
                                    for (let k = 0; k < currencies.length; k++) {
                                        if (currencies[k].code === planCoverage[j].cost_currency) {
                                            option += '<option value=' + currencies[k].code + ' selected>' + currencies[k].code + '</option>';
                                        } else {
                                            option += '<option value=' + currencies[k].code + '>' + currencies[k].code + '</option>';
                                        }
                                    }
                                }
                            }
                        } else {
                            totalcost = '<input type="number" name="plan_coverages[' + i + '][total_cost]" class="form-control" required>';
                            for (let k = 0; k < currencies.length; k++) {
                                option += '<option value=' + currencies[k].code + '>' + currencies[k].code + '</option>';
                            }
                        }

                        $('#plan_cost').append('<div class="row"> <label class="col-md-3 col-sm-3">' + value + '</label>  ' +
                            '<div class="form-group">\n' +
                            '            <div class="input-group">\n' + totalcost +
                            '             <span class="input-group-append"> ' +
                            '<select class=" form-control selectedCurrency" onchange="handleSelectedCurrency(this)" id="selectedCurrency"\n' +
                            '                       style="overflow: hidden; width:4.5rem"\n' +
                            '                      tabindex="1" name="plan_coverages[' + i + '][cost_currency]">\n' + option +
                            '        </select>\n' +
                            '</span>' +
                            // '                <input value="" class="form-control" name="plan_coverages[' + i + '][total_cost]" type="text">\n' +
                            // '                <div class="input-group-btn bs-dropdown-to-select-group">\n' +
                            // '                    <button type="button" class="btn btn-default dropdown-toggle as-is bs-dropdown-to-select"\n' +
                            // '                    data-toggle="dropdown"> <span class="" data-bind="bs-drp-sel-label">Select...</span>\n' +
                            // '\n' +
                            // '                        <input class=""\n' +
                            // '                        name="plan_coverages[' + i + '][cost_currency]" data-bind="bs-drp-sel-value" value="" type="hidden"> <span class="caret"></span>\n' +
                            // ' <span class="sr-only">Toggle Dropdown</span>\n' +
                            // '\n' +
                            // '                    </button>\n' +
                            // '                    <select class="dropdown-menu" style="">\n' +
                            // '                        <option ><a class="" href=" ">USD</a>\n' +
                            // '                        </option>\n' +
                            // '                        <option ><a class="" href=" ">CAD</a>\n' +
                            // '                        </option>\n' +
                            // '                        <option ><a class="" href=" ">PKR</a>\n' +
                            // '                        </option>\n' +
                            // '                    </select>\n' +
                            //
                            // '                </div>\n' +
                            // '            </div>' +
                            '</div></div>');
                        // $('#plan_cost').
                        // jQuery("label[for='myalue']").html("asd").appendTo('#plan_cost');
                        // console.log(jQuery("label[for='myalue']").html("asd").appendTo('#plan_cost'));
                        option = "";
                        totalcost = "";
                    });
                });
                var planCost = {value: "{!! $benefitPlan->plan_cost_rate !!}"};
                onChange(planCost);
                myFunction();
            });


            function myFunction() {
                var textarea = "{!!  $benefitPlan->description !!}"
                document.getElementById("description").value = textarea;
            }


        });
    </script>
@endsection

@endsection



