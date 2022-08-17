@extends('layouts.admin')
@section('title','Add Employee Benefit Status')
@section('heading')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{__('language.Add')}} {{__('language.Benefit')}} {{__('language.Status')}}
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
                        <li class="breadcrumb-item">{{__('language.Employee')}}</li>
                        <li class="breadcrumb-item active">{{__('language.Benefit Status')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@stop
@section('content')
    <!-----Show error messages---->
    @if (Session::has('error'))
        <div class="alert alert-warning" align="left">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>!</strong> {{Session::get('error')}}
        </div>
    @endif
    <!-------end: Show error messages----->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <!-------back Button---->
                <div style="margin-top:10px; margin-right: 10px;">
                    <button type="button"
                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees/'.$employeeID.'/benefit-details')}} @else {{url('en/employees/'.$employeeID.'/employee-details')}} @endif'"
                            class="btn btn-info float-right">{{__('language.Back')}}</button>
                </div>
                <!-------/.back Button----->
                <div class="card-body">
                    <form action="@if(isset($locale)){{url($locale.'/employee/benefit-status/'.$employeeID.'-'.$groupPlanID.'-'.$status.'/store')}} @else {{url('en/employee/benefit-status/'.$employeeID.'-'.$groupPlanID.'-'.$status.'/store')}} @endif"
                          method="post" class="form-horizontal needs-validation" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-body">
                            <hr class="m-t-0 m-b-40">
                            <!-------row----->
                            <div class="row">
                                <!--------Effective Date------>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">{{__('language.Effective')}}
                                            {{__('language.Date')}}</label>
                                        <div class="col-md-9">
                                            <input type="date" name="effective_date" @if(isset($employeeBenefitStatus['status']->effective_date)) value="{{$employeeBenefitStatus['status']->effective_date}}" @endif class="form-control"
                                                   required>
                                            <input type="hidden" name="group_plan_ID" value="{{$groupPlanID}}"
                                                   class="form-control">
                                            <input type="hidden" name="employee_ID" value="{{$employeeID}}"
                                                   class="form-control">
                                            <input type="hidden" name="status" value="{{$status}}" class="form-control">
                                            <input type="hidden" name="deduction_frequency"
                                                   value="{{$benefitData['groupPlan']['groups']['payperiod']}}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <!--------/.Effective Date------>
                            </div>

                            <!---row--->
                            <hr>
                            <br>
                            <!---row--->

                            @if(isset($benefitData['planFields']['requireCoverage']) && $status=='enroll')
                                @if(isset($benefitData['benefitPlanDetails']['planCoverages']))
                                    <div class="row">
                                        <!--------Benefit Coverage------>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="control-label col-md-3">{{__('language.Coverage')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <select class="form-control custom-select"
                                                            data-placeholder="Select a Coverage Option" tabindex="1"
                                                            name="coverage">
                                                        <option value=""
                                                                hidden>{{__('language.Select')}} {{__('language.Coverage')}} {{__('language.Option')}}</option>
                                                        @foreach($benefitData['benefitPlanDetails']['planCoverages'] as $coverages)
                                                            <option @if(isset($employeeBenefitStatus['employee']->employee_benefit_plan_coverage) && $employeeBenefitStatus['employee']->employee_benefit_plan_coverage == $coverages['coverage_name'])  selected @endif value="{{$coverages['coverage_name']}}">{{str_replace('_',' ', $coverages['coverage_name'])}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <!--------/.Benefit Coverage------>
                                        </div>

                                    </div>
                                @endif
                            @endif
                        <!-------row----->
                            @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) || isset($benefitData['planFields']['requireCost']) && $status=='enroll' )
                                <div class="row">
                                    <!------------Employee Pays-------->
                                    <div class="col-md-6">
                                        <div class="  row">
                                            <label class="control-label col-md-3">{{__('language.Employee')}}
                                                {{__('language.Pays')}}</label>
                                            <!-------Currency or Percentage Option for Employee--->
                                            @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) )
                                                <div class="btn-group btn-group-toggle typeToggle"
                                                     id="employeeToggleType" data-toggle="buttons">
                                                    <label class="btn bg-olive
                                                    @if(isset($employeeBenefitStatus['employee_payment']) && $employeeBenefitStatus['employee_payment']['employeePaysType'] == "currency") active @endif">
                                                        <input type="radio" name="employeePaysType" value="currency"
                                                               autocomplete="off"> $
                                                    </label>
                                                    <label class="btn bg-olive  @if(isset($employeeBenefitStatus['employee_payment']) && $employeeBenefitStatus['employee_payment']['employeePaysType'] == "percent") active @endif">
                                                        <input type="radio" name="employeePaysType" value="percent"
                                                               autocomplete="off"> %
                                                    </label>
                                                </div>
                                        @endif
                                        <!-------./Currency or Percentage Option for Employee--->
                                            <div class="col-md-7 ">
                                                @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) || isset($benefitData['planFields']['requireCost']) && $status=='enroll' )
                                                    <div class="input-group employee-currency-div " id="">
                                                        <input type="hidden" name="employeePaysType" value="currency">
                                                        <input type="number" name="employeePaysAmount"
                                                               class="form-control" @if(isset($employeeBenefitStatus['employee_payment'])
                                                                && $employeeBenefitStatus['employee_payment']['employeePaysType']=="currency") value="{{$employeeBenefitStatus['employee_payment']['employeePaysAmount']}}" @endif>
                                                        <span class="input-group-append">
              <select class=" form-control selectedCurrency" id="selectedCurrency"
                      onchange="$('.selectedCurrency').val(this.value)" style="overflow: hidden; width:4.5rem"
                      tabindex="1" name="employeeCurrencyCode">
                  @foreach($currencies as $currency)
                      <option value="{{$currency->code}}" @if(isset($employeeBenefitStatus['employee_payment'])
                                                                && $employeeBenefitStatus['employee_payment']['employeePaysType']=="currency" && isset($employeeBenefitStatus['employee_payment']['employeeCurrencyCode']))
                      @if($employeeBenefitStatus['employee_payment']['employeeCurrencyCode'] == $currency->code) selected @endif
                      @else
                      @if($currency->code == 'USD' ) selected
                              @endif
                              @endif  label="{{$currency->code}} - {{$currency->name}}"></option>
                  @endforeach

        </select>
</span>
                                                    </div>
                                                @endif
                                                @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) )
                                                    <div class="input-group employee-percentage-div" id=""
                                                         style="display: none">
                                                        <input type="hidden" name="employeePaysType" value="percent">
                                                        <input type="number" name="employeePaysAmount"
                                                               @if(isset($employeeBenefitStatus['employee_payment'])
                                                        && $employeeBenefitStatus['employee_payment']['employeePaysType']=="percent") value="{{$employeeBenefitStatus['employee_payment']['employeePaysAmount']}}" @endif
                                                               class="form-control rounded-0">
                                                        <span class="input-group-append">
<button type="button" class="btn btn-outline-secondary btn-flat disabled">%</button>
</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!------------/.Employee Pays-------->
                                </div>

                                <br>
                                <!-------row----->
                                <div class="row">
                                    <!------------Company Pays-------->
                                    <div class="col-md-6">
                                        <div class="  row">
                                            <label class="control-label col-md-3">{{__('language.Company')}}
                                                {{__('language.Pays')}}</label>
                                            <!-------Currency or Percentage Option for Company--->
                                            @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) )
                                                <div class="btn-group btn-group-toggle typeToggle"
                                                     id="companyToggleType" data-toggle="buttons">
                                                    <label class="btn bg-olive @if(isset($employeeBenefitStatus['company_payment']) && $employeeBenefitStatus['company_payment']['companyPaysType'] == "currency") active @endif">
                                                        <input type="radio" name="companyPaysType" value="currency"
                                                               autocomplete="off"> $
                                                    </label>
                                                    <label class="btn bg-olive @if(isset($employeeBenefitStatus['company_payment']) && $employeeBenefitStatus['company_payment']['companyPaysType'] == "percent") active @endif">
                                                        <input type="radio" name="companyPaysType" value="percent"
                                                               autocomplete="off"> %
                                                    </label>
                                                </div>
                                            @endif
                                            <div class="col-md-7">
                                                @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) || isset($benefitData['planFields']['requireCost']) && $status=='enroll' )
                                                    <div class="input-group company-currency-div">
                                                        <input type="hidden" name="companyPaysType" value="currency"
                                                               class="form-control">
                                                        <input type="number" name="companyPaysAmount"
                                                               @if(isset($employeeBenefitStatus['company_payment'])
                                                                && $employeeBenefitStatus['company_payment']['companyPaysType']=="currency") value="{{$employeeBenefitStatus['company_payment']['companyPaysAmount']}}" @endif
                                                               class="form-control">
                                                        <span class="input-group-append">
                 <select class=" form-control selectedCurrency" id="selectedCurrency"
                         onchange="$('.selectedCurrency').val(this.value)" style="overflow: hidden; width:4.5rem"
                         tabindex="1" name="companyCurrencyCode">
                     @foreach($currencies as $currency)
                         <option value="{{$currency->code}}" @if(isset($employeeBenefitStatus['company_payment'])
                                                                && $employeeBenefitStatus['company_payment']['companyPaysType']=="currency" && isset($employeeBenefitStatus['company_payment']['companyCurrencyCode']))
                         @if($employeeBenefitStatus['company_payment']['companyCurrencyCode'] == $currency->code) selected @endif
                                 @else
                                 @if($currency->code == 'USD' ) selected
                                 @endif
                                 @endif label="{{$currency->code}} - {{$currency->name}}"></option>
                     @endforeach

        </select>
</span>
                                                    </div>
                                                @endif
                                                @if(isset($benefitData['planFields']['hasMultiplePaymentOptions']) )
                                                    <div class="input-group company-percentage-div"
                                                         style="display: none">
                                                        <input type="number" name="companyPaysAmount"
                                                               @if(isset($employeeBenefitStatus['company_payment'])
                                                        && $employeeBenefitStatus['company_payment']['companyPaysType']=="percent") value="{{$employeeBenefitStatus['company_payment']['companyPaysAmount']}}" @endif
                                                               class="form-control rounded-0">
                                                        <input type="hidden" name="companyPaysType"
                                                               class="form-control rounded-0" value="percent">
                                                        <span class="input-group-append">
<button type="button" class="btn btn-outline-secondary btn-flat disabled">%</button>
</span>
                                                    </div>
                                                @endif


                                            </div>

                                        </div>
                                    </div>
                                    <!------------/.Company Pays-------->
                                </div>
                        @endif

                        <!---row--->
                            <div class="row ">
                                <!--------Comment------>
                                <div class="col-md-9">
                                    <label class="control-label col-md-3 "><b>{{__('language.Comment')}}:</b></label>
                                    <br>
                                    <!------Comment------>
                                    <div class="mb-3">
<textarea class="form-control" name="comment"
          placeholder="{{__('language.Enter')}} {{__('language.text')}} ..."
          style="width: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!------/.Form Body------->
                </div>
                <!-------/.Card Body----->
                <!-----Line Break------->
                <hr class="m-t-0 m-b-40">

                <!-------Form Actions------->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="row">
                                <div class="offset-md-1 col-md-11">
                                    <button type="submit" class="btn btn-success">{{__('language.Add')}}
                                        {{__('language.Status')}}</button>
                                    <button type="button"
                                            class="btn btn-inverse">{{__('language.Cancel')}}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br>
                </div>
                <!---------/. Form Actions------->
                </form>
                <!-------/. Form End------>
            </div>
            <!------/. card ----->
        </div>
        <!-----/. col------>
    </div>
    <!--------/.row------>
    @push('scripts')
        <script>
            $(document).ready(function () {
                function employeePaysType(radioBtnValue, radioBtnName )
                {
                        if (radioBtnValue == "currency") {
                            $('.employee-currency-div').show().find('input, textarea').prop('disabled', false);
                            $('.employee-percentage-div').find('input, textarea').prop('value',"");
                            $('.employee-percentage-div').hide().find('input, textarea').prop('disabled', true);


                        } else if (radioBtnValue == "percent") {
                            $('.employee-currency-div').find('input, textarea').prop('value', "");
                            $('.employee-currency-div').hide().find('input, textarea').prop('disabled', true);
                            $('.employee-percentage-div').show().find('input, textarea').prop('disabled', false);
                        }

                }
                function companyPaysType(radioBtnValue, radioBtnName )
                {

                        if (radioBtnValue == "currency") {
                            $('.company-currency-div').show().find('input, textarea').prop('disabled', false);
                            $('.company-percentage-div').find('input, textarea').prop('value', "");
                            $('.company-percentage-div').hide().find('input, textarea').prop('disabled', true);
                        } else if (radioBtnValue == "percent") {
                            $('.company-currency-div').find('input, textarea').prop('value', "");
                            $('.company-currency-div').hide().find('input, textarea').prop('disabled', true);
                            $('.company-percentage-div').show().find('input, textarea').prop('disabled', false);
                        }


                }
                var radioBtnValue = $("#employeeToggleType label.active input:radio").val();
                var radioBtnName = $("#employeeToggleType input:radio").attr('name');
                if(radioBtnName == "employeePaysType"){employeePaysType(radioBtnValue, radioBtnName);}

                var radioBtnValue = $("#companyToggleType label.active input:radio").val();
                var radioBtnName = $("#companyToggleType input:radio").attr('name');
                if (radioBtnName == "companyPaysType") {
                    companyPaysType(radioBtnValue, radioBtnName);
                }
                $(".typeToggle input:radio").change(function () {
                    var radioBtnValue = $(this).val();
                    var radioBtnName = $(this).attr('name');
                    if (radioBtnName == "employeePaysType") {
                        employeePaysType(radioBtnValue, radioBtnName);

                    } else if (radioBtnName == "companyPaysType") {
                        companyPaysType(radioBtnValue, radioBtnName );
                    }

                });
            })

        </script>

    @endpush
@stop
