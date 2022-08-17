@extends('layouts/contentLayoutMaster')
@section('title','Benefit Group')
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
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#account-details">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">1</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 1</span>
                                        <span class="bs-stepper-subtitle">Group Detail</span>
                                      </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#personal-info">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">2</span>
                                    <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Step 2</span>
                                    <span class="bs-stepper-subtitle">Select Employees</span>
                                  </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#address-step">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">3</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Step 3</span>
                                        <span class="bs-stepper-subtitle">Select Plans</span>
                                      </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <form role="form"
                                  action="@if(isset($locale)){{url($locale.'/benefitgroup')}} @else {{url('en/benefitgroup')}} @endif"
                                  id="submit_form" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div id="account-details" class="content">
                                    <fieldset>
                                        <p>A Benefit Group is a set of people who are all assigned the same benefit
                                            plans.
                                            Benefit groups are often used because different people may be offered
                                            different
                                            benefit packages based on pay period, location, position, or tenure. Most
                                            companies have only one or two benefit groups.</p>

                                        <div class="row">

                                            <div class="form-group col-md-6">
                                                <label for="name">Group Name</label><span class="text-danger"> *</span>
                                                <input type="text" class="form-control" maxlength="100" name="name"
                                                       id="name">
                                                <small id="helpId" class="form-text text-muted">e.g. Full Time
                                                    Employee</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="control-label text-right">How often will deductions
                                                    happen?</label><span class="text-danger"> *</span>
                                                <select class="form-control custom-select" name="payperiod"
                                                        id="payperiod">
                                                    <option value="daily">Daily</option>
                                                    <option value="weekly">Weekly</option>
                                                    <option value="every_other_week">Every other week</option>
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Qarterly</option>
                                                    <option value="twice_year">Twice a year</option>
                                                    <option value="yearly">Yearly</option>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="d-flex justify-content-between">
{{--                                        <button type="button" class="btn btn-outline-secondary btn-prev" disabled>--}}
{{--                                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>--}}
{{--                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>--}}
{{--                                        </button>--}}
                                        <a
                                                href="@if(isset($locale)){{url($locale.'/benefitgroup')}}
                                                @else {{url('en/benefitgroup')}} @endif"
                                                class="btn btn-outline-warning">{{__('language.Cancel')}}</a>
                                        <a class="btn btn-primary btn-next">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                                        </a>
                                    </div>
                                </div>
                                <div id="personal-info" class="content">
                                    <fieldset>
                                        <div class="row pl-2 pr-2 pb-2" id="step-2">
                                            <p>Select the Employees you would like to add to this Group. Remember,
                                                Employees
                                                can only be in one Group at a time. If you move them from another Group,
                                                they will lose the benefits of their previous group.</p>

                                            <p>Which employees would you like to include in this Benefit Group?</p>
                                        </div>
                                        <div class="add_employees_hidden">
                                        </div>
                                        <div class="row pl-1 pr-1">
                                            <div class="col-md-6">
                                                <div class="card border border-secondary">
                                                    <div class="card-header">
                                                        <button type="button"
                                                                class="btn btn-primary btn-rounded float-right ml-1 mt-1"
                                                                id="add-employee">Add
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="list-group" id="left-list">
                                                            @foreach($employees as $employee)
                                                                <li value="{{$employee->id}}" class="list-group-item">
                                                                    {{$employee->firstname}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="card border border-secondary">
                                                    <div class="card-header">
                                                        <button type="button"
                                                                class="btn btn-primary btn-rounded float-right ml-1 mt-1"
                                                                id="remove-employee">Remove
                                                        </button>
                                                    </div>
                                                    <div class="card-body" id="right-list">
                                                        <ul class="list-group">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next" id="plan_costs"
                                                onClick="employeedata()">
                                            <span class="align-middle d-sm-inline-block d-none">Next</span>
                                            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
                                        </button>
                                    </div>

                                </div>
                                <div id="address-step" class="content">
                                    <fieldset>
                                        {{--                                            <div class="col-md-12">--}}
                                        {{--                                                --}}
                                        {{--                                            </div>--}}
                                        <div class="row">
                                            <div class="col-md-9">
                                                <p>Select the Plans you would like to apply to this group. When you add
                                                    the plan,
                                                    we will ask you when employees in this Group will become eligible,
                                                    as well
                                                    as the employee cost for each coverage option.</p>
                                                <div id=add-plan>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card border border-secondary">
                                                    <div class="card-header pt-1 pl-1">
                                                        <p>Available Plans</p>
                                                    </div>
                                                    {{--                                                        <img class="card-img-top">--}}

                                                    <div class="card-body">
                                                        <ul class="list-group" id="plan-list">
                                                            @foreach($benefitPlans as $benefitPlan)
                                                                <li class="list-group-item"
                                                                    value="{{$benefitPlan->id}}">
                                                                    {{$benefitPlan->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary btn-prev">
                                            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button class="btn btn-success btn-submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/benefit/benefit-group/create.js')) }}"></script>
@endsection
@stop



