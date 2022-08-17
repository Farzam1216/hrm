@extends('layouts/contentLayoutMaster')
@section('title','Benefit Plan')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="duplicate-plan"
                            action="@if(isset($locale)){{url($locale.'/benefit-plan/duplicateBenefitPlan/{id}/save')}} @else {{url('en/benefit/benefit-plan/duplicateBenefitPlan/{id}/save')}} @endif"
                            method="post">
                        {{ csrf_field() }}
                        @if (str_contains($planType, '_'))
                            <h4 class="  ">Duplicate {{strtoupper(str_replace('_',' ',$planType->name))}} Plan</h4>
                        @else
                            <h4 class=" ">Duplicate {{strtoupper($planType->name)}} Plan</h4>
                        @endif
                        <br>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="duplicatePlanName">Plan Name</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control" value="{{$planType->name}}"
                                       name="duplicatePlanName" id="duplicatePlanName"
                                       placeholder="e.g. SelectHealth - Platinum">
                            </div>
                            <div class="col-md-6 form-group date-range">
                                <label class="control-label text-right">{{__('language.Date')}} {{__('language.Range')}}</label><span
                                        class="text-danger"> *</span>
                                <input type="text" id="date_range" name="date_range"
                                       class="form-control flatpickr-range" value="{{$planType->start_date. ' to '.$planType->end_date}}" placeholder="YYYY-MM-DD to YYYY-MM-DD"/>
                                <input type="hidden" name="plan_id" value="{{request()->route('id')}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <a type="button"
                                   href='@if(isset($locale)){{url($locale.'/benefit-plan')}}
                                   @else {{url('en/benefit-plan')}} @endif'
                                   class="btn btn-outline-warning ">{{__('language.Cancel')}}</a>
                                <button class="btn btn-primary float-right btn-submit ">{{__('language.Duplicate')}} {{__('language.Benefit')}} {{__('language.Plan')}}</button>
                            </div>
                        </div>
                    <!-- <button  type="submit" class="btn btn-primary mb-1 mb-sm-0  mr-0 mr-sm-1 waves-effect waves-float waves-light float-right" >{{__('language.Add')}} {{__('Plan')}} </button> -->

                    </form>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
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
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-duplicate-benefit-plan.js')) }}"></script>
    {{-- Page js files --}}
@endsection
@stop