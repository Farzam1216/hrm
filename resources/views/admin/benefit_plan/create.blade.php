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

                        @if($planType == 'health' || $planType == 'dental' || $planType == 'vision' || $planType == 'supplemental_health')
                            @if (str_contains($planType, '_'))
                                <h4 class="px-2 pt-2">New {{strtoupper(str_replace('_',' ',$planType))}} Plan</h4>
                            @else
                                <h4 class="px-2 pt-2">New {{strtoupper($planType)}} Plan</h4>
                            @endif
                            <div class="bs-stepper-header">
                                <div class="step stepwizard-step" data-target="#account-details">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">1</span>
                                        <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 1</span>
                                        <span class="bs-stepper-subtitle">Plan Details</span>
                                      </span>
                                    </button>
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
                                </div>
                                {{--                                </div>--}}
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                {{--                                <div class="stepwizard-step ">--}}
                                <div class="step stepwizard-step" data-target="#address-step">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-box">3</span>
                                        <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 3</span>
                                        <span class="bs-stepper-subtitle">Plan Cost</span>
                                      </span>
                                    </button>
                                </div>
                                {{--                                </div>--}}
                            </div>
                            <div class="bs-stepper-content">
                                <form role="form" id="submit_form"
                                      action="@if(isset($locale)){{url($locale.'/benefit-plan')}}@else{{url('en/benefit/benefit-plan')}}@endif"
                                      method="post">
                                    {{csrf_field()}}
                                    <div id="account-details" class="content">
                                        <fieldset>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="plan_name">Plan Name</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="text" class="form-control" name="plan_name"
                                                           id="plan_name" placeholder="e.g. Select Health - Platinum">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label text-right">{{__('language.Date')}} {{__('language.Range')}}</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="text" name="date_range" id="date_range"
                                                           class="form-control flatpickr-range"
                                                           placeholder="YYYY-MM-DD to YYYY-MM-DD"/>

                                                </div>
                                            </div>

                                            <div class="row">
                                                <input type="text" name="plan_id" id="plan_id"
                                                       value="{{$benefitViewFields->id}}" hidden>
                                                <div class="form-group col-md-6">
                                                    <label for="rate_select">Rate</label><span
                                                            class="text-danger"> *</span>
                                                    <select class="form-control" name="rate_select"
                                                            onchange="onChange(this);" id="rate_select">
                                                        <option value="">-Select-</option>
                                                        <option value="Standard">Standard (Composite rate)</option>
                                                        <option value="Variable">Variable <small>e.g. Age based
                                                                rate</small>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label">{{__('language.Description')}} <span
                                                                class="text-danger"> *</span></label>
                                                    <textarea class="form-control" name="description" id="description"
                                                              cols="30" rows="3"
                                                              placeholder="{{__('language.Enter')}} {{__('language.Description')}} {{__('language.Here')}}"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="Url">Plan Url</label>
                                                    <input class="form-control" type="text" name="plan_URL"
                                                           id="plan_URL">
                                                    <small class="">
                                                        Note: We recommend adding the URL of your Plan's find-a-doctor
                                                        page or another page that you think would be most useful to your
                                                        employees
                                                    </small>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="d-flex justify-content-between">
                                            <a
                                                    href="@if(isset($locale)){{url($locale.'/benefit-plan')}}
                                                    @else {{url('en/benefit-plan')}} @endif"
                                                    class="btn btn-outline-warning ">{{__('language.Cancel')}}</a>
                                            <a class="btn btn-primary float-right btn-next">
                                                <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                <i data-feather="arrow-right"
                                                   class="align-middle ml-sm-25 ml-0"></i>
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
                                                                   id="customCheck1" value="Employee"/>
                                                            <label class="custom-control-label" for="customCheck1">Employee</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck2" value="Employee Spouse"/>
                                                            <label class="custom-control-label" for="customCheck2">Employee
                                                                Spouse</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck3" value="Employee Child"/>
                                                            <label class="custom-control-label" for="customCheck3">Employee
                                                                Child</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck4" value="Employee Children"/>
                                                            <label class="custom-control-label" for="customCheck4">Employee
                                                                Children</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck5" value="Two Party"/>
                                                            <label class="custom-control-label" for="customCheck5">Two
                                                                Party</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input chk"
                                                                   name="plan_coverages[][plan_coverage_type]"
                                                                   id="customCheck6" value="Family Only"/>
                                                            <label class="custom-control-label" for="customCheck6">Family
                                                                Only<small> Employee not included</small></label>
                                                        </div>
                                                        {{--                                                        <div class="custom-control custom-checkbox">--}}
                                                        {{--                                                            <input type="checkbox" class="form-check-input chk" name="plan_coverages[][plan_coverage_type]"  value="Employee">--}}
                                                        {{--                                                         <label class="custom-control-label">--}}
                                                        {{--                                                            Employee--}}
                                                        {{--                                                        </label><br>--}}
                                                        {{--                                                        <label class="form-check-label">--}}
                                                        {{--                                                            <input type="checkbox" class="form-check-input chk" name="plan_coverages[][plan_coverage_type]" id="employee+spouse" value="Employee Spouse">--}}
                                                        {{--                                                            Employee + Spouse--}}
                                                        {{--                                                        </label><br>--}}
                                                        {{--                                                        <label class="form-check-label">--}}
                                                        {{--                                                            <input type="checkbox" class="form-check-input chk" name="plan_coverages[][plan_coverage_type]" id="employee+child" value="Employee Child">--}}
                                                        {{--                                                            Employee + Child--}}
                                                        {{--                                                        </label><br>--}}
                                                        {{--                                                        <label class="form-check-label">--}}
                                                        {{--                                                            <input type="checkbox" class="form-check-input chk" name="plan_coverages[][plan_coverage_type]" id="employee+children" value="Employee + Children">--}}
                                                        {{--                                                            Employee + Children--}}
                                                        {{--                                                        </label><br>--}}
                                                        {{--                                                        <label class="form-check-label">--}}
                                                        {{--                                                            <input type="checkbox" class="form-check-input chk" name="plan_coverages[][plan_coverage_type]" id="two_party" value="Two Party">--}}
                                                        {{--                                                            Two Party--}}
                                                        {{--                                                        </label><br>--}}
                                                        {{--                                                        <label class="form-check-label">--}}
                                                        {{--                                                            <input type="checkbox" class="form-check-input chk" name="plan_coverages[][plan_coverage_type]" id="family_only" value="Family Only">--}}
                                                        {{--                                                            Family Only <small> Employee not included</small>--}}
                                                        {{--                                                        </label><br>--}}
                                                        {{--                                                        <div class="hidden_plan_coverages"></div>--}}
                                                        {{--                                                    </div>--}}
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
                                            <button type="button" class="btn btn-primary btn-prev" id="empty_div">
                                                <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                            </button>
                                            <button class="btn btn-success btn-submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @elseif($planType == 'retirement' || $planType == 'reimbursement' || $planType == 'health_savings_account' || $planType == 'flex_spending_account'|| $planType == 'life_insurance'|| $planType == 'disability'|| $planType == 'other')
                            @if (str_contains($planType, '_'))
                                <h4 class="px-2 pt-2">New {{strtoupper(str_replace('_',' ',$planType))}} Plan</h4>
                            @else
                                <h4 class="px-2 pt-2">New {{strtoupper($planType)}} Plan</h4>
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
                                      action="@if(isset($locale)){{url($locale.'/benefit-plan')}}@else{{url('en/benefit/benefit-plan')}}@endif"
                                      method="post">
                                    {{csrf_field()}}
                                    <div id="account-details" class="content">
                                        <fieldset>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="plan_name">Plan Name</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="text" class="form-control" name="plan_name"
                                                           id="plan_name" placeholder="e.g. Select Health - Platinum">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label text-right">{{__('language.Date')}} {{__('language.Range')}}</label><span
                                                            class="text-danger"> *</span>
                                                    <input type="text" name="date_range" id="date_range"
                                                           class="form-control flatpickr-range"
                                                           placeholder="YYYY-MM-DD to YYYY-MM-DD"/>

                                                </div>
                                            </div>


                                            <input type="text" name="plan_id" id="plan_id"
                                                   value="{{$benefitViewFields->id}}" hidden>
                                            @if($planType == 'reimbursement')
                                                <p>The company will reimburse up to...</p>
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="reimbursement_amount">Amount</label><span
                                                                class="text-danger"> *</span>
                                                        <input type="number" class="form-control" placeholder="Amount"
                                                               name="reimbursement_amount" id="reimbursement_amount">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="reimbursement_currency">Currency</label><span
                                                                class="text-danger"> *</span>
                                                        <select class="form-control" name="reimbursement_currency">
                                                            @foreach($currencies as $currency)
                                                            <option value="{{$currency->code}}">{{$currency->code}}</option>
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
                                                            <option value="">Select</option>
                                                            <option value="every_pay_period">Every Pay Period</option>
                                                            <option value="every_month">Every Month</option>
                                                            <option value="every_quater">Every Quarter</option>
                                                            <option value="every_year">Every Year</option>
                                                            <option value="one_time">One Time</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif( $planType == 'flex_spending_account')
                                                <p>The company will reimburse up to...</p>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="rate_select">Rate</label><span class="text-danger"> *</span>
                                                        <select class="form-control" name="rate_select"
                                                                id="rate_select">
                                                            <option value="">-Select-</option>
                                                            <option value="General Healthcare Purposes">General
                                                                Healthcare Purposes <small>(Most Common)</small>
                                                            </option>
                                                            <option value="Dependent Care Purposes">Dependent Care
                                                                Purposes
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">{{__('language.Description')}}</label>
                                            <textarea class="form-control" name="description" id="description" cols="30"
                                                      rows="3"
                                                      placeholder="{{__('language.Enter')}} {{__('language.Description')}} {{__('language.Here')}}"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="Url">Plan Url</label>
                                            <input class="form-control" type="text" name="plan_URL" id="plan_URL">
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
                                               class="btn btn-outline-warning ml-0 mt-2">{{__('language.Cancel')}}</a>
                                            <button class="btn btn-primary float-right btn-submit mt-2">{{__('language.Add')}} {{__('language.Benefit')}} {{__('language.Plan')}}</button>
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
    {{-- Page js files --}}
    <script>
        const onChange = (sel) => {
            if (sel.value === 'Variable') {
                var hidden = $('.stepwizard-step');
                hidden = hidden[2];
                $(hidden).hide();
                $('.variable_rate_remove').hide();
                var $input = $('<button class="btn btn-success nextBtn pull-right hidden_plan_cost align-middle d-sm-inline-block d-none" style="float: right;"\n' +
                    '                                                type="submit">{{__('language.Add')}} {{__('language.Benefit Plan')}}\n' +
                    '                                        </button>');
                $(".submit").append($input);

            } else if (sel.value === 'Standard') {
                var hidden = $('.stepwizard-step');
                hidden = hidden[2];
                $(hidden).show();
                $('.variable_rate_remove').show();
                $("#rate_select").on("click", function () {
                    $(".submit").empty();
                });

            }
        }

        $("#empty_div").on("click", function () {
            $("#plan_cost").empty();
        });
        $("#plan_costs").on("click", function () {
            var currencies = {!! $currencies !!};
            var option = '';
            var checkedIds = $(".chk:checked").map(function () {
                return $(this).val();
            }).toArray();
            for (let k = 0; k < currencies.length; k++) {
                option += '<option value=' + currencies[k].code + '>' + currencies[k].code + '</option>';
            }
            $('#plan_cost').html('');
            checkedIds.forEach(function (value, i) {
                $('#plan_cost').append('<div class="row"> <span class="col-md-3 col-sm-3 ">' + value + '</span>  ' +
                    '<div class="form-group">\n' +
                    '            <div class="input-group">\n' +
                    '                <input value="" class="form-control" name="plan_coverages[' + i + '][total_cost]" type="number" required>\n' +
                    '                <div class="input-group-btn bs-dropdown-to-select-group">\n' +
                    '                        <select class=" form-control selectedCurrency" onchange="handleSelectedCurrency(this)" id="selectedCurrency" name="plan_coverages[' + i + '][cost_currency]" id="">\n' +
                    '                       style="overflow: hidden; width:4.5rem"\n' +
                    '                      tabindex="1" >\n' + option +
                    '</select>\n' +

                    '                </div>\n' +
                    '            </div></div></div><br>');
            });
        });

        function handleSelectedCurrency(ele) {
            $('.selectedCurrency').val($(ele).val());
        }
    </script>
@endsection
@stop



