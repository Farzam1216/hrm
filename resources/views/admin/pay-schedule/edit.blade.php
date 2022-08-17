@extends('layouts.contentLayoutMaster')
@section('title','Edit Pay Schedule')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="dt-action-buttons">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('pay-schedule.index', [$locale])}} @else {{route('pay-schedule.index', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Back"><i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-none"> {{__('language.Back')}}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-1">
                <form id="pay-schedule-form" action="@if(isset($locale)) {{route('pay-schedule.update', [$locale, $paySchedule->id])}} @else {{route('pay-schedule.update', ['en', $paySchedule->id])}} @endif" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="control-label">{{__('language.Name')}} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Pay Schedule')}} {{__('language.Name')}}" value="{{ old('name', $paySchedule->name)}}">
                        </div>
                    </div>

                    <hr>

                    <div class="levels">
                        <div class="level-section current">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">{{__('language.Frequency')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group col-6">
                                    <select class="form-control custom-select pay-schedule-frequency" name="frequency" id="frequency" onchange="payDates();">
                                        <option value="">Select Frequency</option>
                                        <option value="Weekly" @if($paySchedule->frequency == 'Weekly') selected @endif>Weekly</option>
                                        <option value="Every other week" @if($paySchedule->frequency == 'Every other week') selected @endif>Every other week</option>
                                        <option value="Twice a monthly" @if($paySchedule->frequency == 'Twice a monthly') selected @endif>Twice a monthly</option>
                                        <option value="Monthly" @if($paySchedule->frequency == 'Monthly') selected @endif>Monthly</option>
                                        <option value="Quarterly" @if($paySchedule->frequency == 'Quarterly') selected @endif>Quarterly</option>
                                        <option value="Twice a yearly" @if($paySchedule->frequency == 'Twice a yearly') selected @endif>Twice a yearly</option>
                                        <option value="Yearly" @if($paySchedule->frequency == 'Yearly') selected @endif>Yearly</option>
                                    </select>
                                </div>
                            </div>

                            <div class="frequency-type">
                                @if($paySchedule->frequency == 'Weekly')
                                    <div class="row">
                                        <div class="col-12 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                        </div>                               
                                        <div class="form-group col-6 frequency-fields">
                                            <select class="form-control custom-select" name="week_day" id="weekly_week_day" onchange="payDates();">
                                                <option value="">Select Day</option>
                                                <option value="Sunday" @if($paySchedule->period_ends == 'Sunday') selected @endif>Sunday</option>
                                                <option value="Monday" @if($paySchedule->period_ends == 'Monday') selected @endif>Monday</option>
                                                <option value="Tuesday" @if($paySchedule->period_ends == 'Tuesday') selected @endif>Tuesday</option>
                                                <option value="Wednesday" @if($paySchedule->period_ends == 'Wednesday') selected @endif>Wednesday</option>
                                                <option value="Thursday" @if($paySchedule->period_ends == 'Thursday') selected @endif>Thursday</option>
                                                <option value="Friday" @if($paySchedule->period_ends == 'Friday') selected @endif>Friday</option>
                                                <option value="Saturday" @if($paySchedule->period_ends == 'Saturday') selected @endif>Saturday</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($paySchedule->frequency == 'Every other week')
                                @php $everyOtherWeek = explode(',', $paySchedule->period_ends); @endphp
                                    <div class="row">
                                        <div class="form-group accrual-type-fields col-md-2 col-5 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                            <select class="form-control custom-select frequency-week-day" name="week_day" id="every_week_day" onchange="payDates();">
                                                <option value="">Select Day</option>
                                                <option value="Sunday" @if($everyOtherWeek[0] == 'Sunday') selected @endif>Sunday</option>
                                                <option value="Monday" @if($everyOtherWeek[0] == 'Monday') selected @endif>Monday</option>
                                                <option value="Tuesday" @if($everyOtherWeek[0] == 'Tuesday') selected @endif>Tuesday</option>
                                                <option value="Wednesday" @if($everyOtherWeek[0] == 'Wednesday') selected @endif>Wednesday</option>
                                                <option value="Thursday" @if($everyOtherWeek[0] == 'Thursday') selected @endif>Thursday</option>
                                                <option value="Friday" @if($everyOtherWeek[0] == 'Friday') selected @endif>Friday</option>
                                                <option value="Saturday" @if($everyOtherWeek[0] == 'Saturday') selected @endif>Saturday</option>
                                            </select>
                                        </div>
                                        <div class="form-group m-0 frequency-fields col-md-3 col-5">
                                            <label id="Weekday"></label>
                                            <select class="form-control custom-select" id="Weekdays" name="dates" onchange="payDates();">
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($paySchedule->frequency == 'Twice a monthly')
                                @php $twiceAMonth = explode(',', $paySchedule->period_ends); @endphp
                                    <div class="row">
                                        <div class="col-12 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="first_date" id="twice_month_first_date" onchange="payDates();">
                                                <option value="1" @if($twiceAMonth[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($twiceAMonth[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($twiceAMonth[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($twiceAMonth[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($twiceAMonth[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($twiceAMonth[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($twiceAMonth[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($twiceAMonth[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($twiceAMonth[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($twiceAMonth[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($twiceAMonth[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($twiceAMonth[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($twiceAMonth[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($twiceAMonth[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($twiceAMonth[0] == '15') selected @endif>15th</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">and the</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="second_date" id="twice_month_second_date" onchange="payDates();">
                                                <option value="16" @if($twiceAMonth[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($twiceAMonth[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($twiceAMonth[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($twiceAMonth[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($twiceAMonth[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($twiceAMonth[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($twiceAMonth[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($twiceAMonth[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($twiceAMonth[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($twiceAMonth[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($twiceAMonth[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($twiceAMonth[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($twiceAMonth[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($twiceAMonth[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of each month</label>
                                    </div>
                                @endif

                                @if($paySchedule->frequency == 'Monthly')
                                    <div class="row">
                                        <div class="col-12 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group col-6 frequency-fields">
                                            <select class="form-control custom-select" name="date" id="monthly_date" onchange="payDates();">
                                                <option value="">Select Day</option>
                                                <option value="1" @if($paySchedule->period_ends == '1') selected @endif>1st</option>
                                                <option value="2" @if($paySchedule->period_ends == '2') selected @endif>2nd</option>
                                                <option value="3" @if($paySchedule->period_ends == '3') selected @endif>3rd</option>
                                                <option value="4" @if($paySchedule->period_ends == '4') selected @endif>4th</option>
                                                <option value="5" @if($paySchedule->period_ends == '5') selected @endif>5th</option>
                                                <option value="6" @if($paySchedule->period_ends == '6') selected @endif>6th</option>
                                                <option value="7" @if($paySchedule->period_ends == '7') selected @endif>7th</option>
                                                <option value="8" @if($paySchedule->period_ends == '8') selected @endif>8th</option>
                                                <option value="9" @if($paySchedule->period_ends == '9') selected @endif>9th</option>
                                                <option value="10" @if($paySchedule->period_ends == '10') selected @endif>10th</option>
                                                <option value="11" @if($paySchedule->period_ends == '11') selected @endif>11th</option>
                                                <option value="12" @if($paySchedule->period_ends == '12') selected @endif>12th</option>
                                                <option value="13" @if($paySchedule->period_ends == '13') selected @endif>13th</option>
                                                <option value="14" @if($paySchedule->period_ends == '14') selected @endif>14th</option>
                                                <option value="15" @if($paySchedule->period_ends == '15') selected @endif>15th</option>
                                                <option value="16" @if($paySchedule->period_ends == '16') selected @endif>16th</option>
                                                <option value="17" @if($paySchedule->period_ends == '17') selected @endif>17th</option>
                                                <option value="18" @if($paySchedule->period_ends == '18') selected @endif>18th</option>
                                                <option value="19" @if($paySchedule->period_ends == '19') selected @endif>19th</option>
                                                <option value="20" @if($paySchedule->period_ends == '20') selected @endif>20th</option>
                                                <option value="21" @if($paySchedule->period_ends == '21') selected @endif>21st</option>
                                                <option value="22" @if($paySchedule->period_ends == '22') selected @endif>22nd</option>
                                                <option value="23" @if($paySchedule->period_ends == '23') selected @endif>23rd</option>
                                                <option value="24" @if($paySchedule->period_ends == '24') selected @endif>24th</option>
                                                <option value="25" @if($paySchedule->period_ends == '25') selected @endif>25th</option>
                                                <option value="26" @if($paySchedule->period_ends == '26') selected @endif>26th</option>
                                                <option value="27" @if($paySchedule->period_ends == '27') selected @endif>27th</option>
                                                <option value="28" @if($paySchedule->period_ends == '28') selected @endif>28th</option>
                                                <option value="last day" @if($paySchedule->period_ends == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($paySchedule->frequency == 'Twice a yearly')
                                @php $twiceAMonth = explode(',', $paySchedule->period_ends); $first = explode('-', $twiceAMonth[0]); $second = explode('-', $twiceAMonth[1]); @endphp
                                    <div class="row">
                                        <div class="col-12 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="first_date" id="twice_year_first_date" onchange="payDates();">
                                                <option value="">Select First Date</option>
                                                <option value="1" @if($first[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($first[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($first[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($first[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($first[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($first[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($first[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($first[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($first[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($first[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($first[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($first[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($first[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($first[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($first[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($first[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($first[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($first[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($first[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($first[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($first[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($first[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($first[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($first[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($first[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($first[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($first[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($first[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($first[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="first_month" id="twice_year_first_month" onchange="payDates();">
                                                <option value="">Select First Month</option>
                                                <option value="1" @if($first[1] == '1') selected @endif>January</option>
                                                <option value="2" @if($first[1] == '2') selected @endif>February</option>
                                                <option value="3" @if($first[1] == '3') selected @endif>March</option>
                                                <option value="4" @if($first[1] == '4') selected @endif>April</option>
                                                <option value="5" @if($first[1] == '5') selected @endif>May</option>
                                                <option value="6" @if($first[1] == '6') selected @endif>June</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="second_date" id="twice_year_second_date" onchange="payDates();">
                                                <option value="">Select Second Date</option>
                                                <option value="1" @if($second[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($second[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($second[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($second[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($second[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($second[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($second[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($second[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($second[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($second[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($second[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($second[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($second[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($second[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($second[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($second[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($second[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($second[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($second[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($second[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($second[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($second[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($second[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($second[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($second[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($second[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($second[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($second[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($second[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="second_month" id="twice_year_second_month" onchange="payDates();">
                                                <option value="">Select Second Month</option>
                                                <option value="7" @if($second[1] == '7') selected @endif>July</option>
                                                <option value="8" @if($second[1] == '8') selected @endif>August</option>
                                                <option value="9" @if($second[1] == '9') selected @endif>September</option>
                                                <option value="10" @if($second[1] == '10') selected @endif>October</option>
                                                <option value="11" @if($second[1] == '11') selected @endif>November</option>
                                                <option value="12" @if($second[1] == '12') selected @endif>December</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($paySchedule->frequency == 'Yearly')
                                @php $yearly = explode('-', $paySchedule->period_ends); @endphp
                                    <div class="row">
                                        <div class="col-12 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="date" id="yearly_date" onchange="payDates();">
                                                <option value="">Select Date</option>
                                                <option value="1" @if($yearly[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($yearly[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($yearly[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($yearly[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($yearly[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($yearly[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($yearly[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($yearly[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($yearly[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($yearly[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($yearly[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($yearly[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($yearly[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($yearly[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($yearly[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($yearly[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($yearly[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($yearly[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($yearly[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($yearly[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($yearly[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($yearly[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($yearly[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($yearly[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($yearly[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($yearly[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($yearly[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($yearly[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($yearly[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="month" id="yearly_month" onchange="payDates();">
                                                <option value="1" @if($yearly[1] == '1') selected @endif>January</option>
                                                <option value="2" @if($yearly[1] == '2') selected @endif>February</option>
                                                <option value="3" @if($yearly[1] == '3') selected @endif>March</option>
                                                <option value="4" @if($yearly[1] == '4') selected @endif>April</option>
                                                <option value="5" @if($yearly[1] == '5') selected @endif>May</option>
                                                <option value="6" @if($yearly[1] == '6') selected @endif>June</option>
                                                <option value="7" @if($yearly[1] == '7') selected @endif>July</option>
                                                <option value="8" @if($yearly[1] == '8') selected @endif>August</option>
                                                <option value="9" @if($yearly[1] == '9') selected @endif>September</option>
                                                <option value="10" @if($yearly[1] == '10') selected @endif>October</option>
                                                <option value="11" @if($yearly[1] == '11') selected @endif>November</option>
                                                <option value="12" @if($yearly[1] == '12') selected @endif>December</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if($paySchedule->frequency == 'Quarterly')
                                @php $quarterly = explode(',', $paySchedule->period_ends); $first = explode('-', $quarterly[0]); $second = explode('-', $quarterly[1]); $third = explode('-', $quarterly[2]); $fourth = explode('-', $quarterly[3]); @endphp
                                    <div class="row">
                                        <div class="col-12 frequency-fields">
                                            <label>Pay periods end on...<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="first_date" id="quarterly_first_date" onchange="payDates();">
                                                <option value="">Select First Date</option>
                                                <option value="1" @if($first[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($first[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($first[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($first[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($first[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($first[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($first[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($first[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($first[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($first[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($first[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($first[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($first[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($first[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($first[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($first[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($first[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($first[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($first[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($first[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($first[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($first[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($first[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($first[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($first[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($first[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($first[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($first[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($first[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="first_month" id="quarterly_first_month" onchange="payDates();">
                                                <option value="1" @if($first[1] == '1') selected @endif>January</option>
                                                <option value="2" @if($first[1] == '2') selected @endif>February</option>
                                                <option value="3" @if($first[1] == '3') selected @endif>March</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="second_date" id="quarterly_second_date" onchange="payDates();">
                                                <option value="">Select Second Date</option>
                                                <option value="1" @if($second[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($second[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($second[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($second[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($second[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($second[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($second[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($second[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($second[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($second[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($second[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($second[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($second[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($second[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($second[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($second[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($second[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($second[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($second[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($second[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($second[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($second[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($second[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($second[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($second[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($second[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($second[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($second[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($second[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="second_month" id="quarterly_second_month" onchange="payDates();">
                                                <option value="4" @if($second[1] == '4') selected @endif>April</option>
                                                <option value="5" @if($second[1] == '5') selected @endif>May</option>
                                                <option value="6" @if($second[1] == '6') selected @endif>June</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="third_date" id="quarterly_third_date" onchange="payDates();">
                                                <option value="">Select Third Date</option>
                                                <option value="1" @if($third[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($third[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($third[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($third[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($third[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($third[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($third[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($third[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($third[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($third[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($third[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($third[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($third[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($third[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($third[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($third[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($third[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($third[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($third[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($third[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($third[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($third[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($third[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($third[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($third[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($third[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($third[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($third[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($third[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="third_month" id="quarterly_third_month" onchange="payDates();">
                                                <option value="7" @if($third[1] == '7') selected @endif>July</option>
                                                <option value="8" @if($third[1] == '8') selected @endif>August</option>
                                                <option value="9" @if($third[1] == '9') selected @endif>September</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="fourth_date" id="quarterly_fourth_date" onchange="payDates();">
                                                <option value="">Select Fourth Date</option>
                                                <option value="1" @if($fourth[0] == '1') selected @endif>1st</option>
                                                <option value="2" @if($fourth[0] == '2') selected @endif>2nd</option>
                                                <option value="3" @if($fourth[0] == '3') selected @endif>3rd</option>
                                                <option value="4" @if($fourth[0] == '4') selected @endif>4th</option>
                                                <option value="5" @if($fourth[0] == '5') selected @endif>5th</option>
                                                <option value="6" @if($fourth[0] == '6') selected @endif>6th</option>
                                                <option value="7" @if($fourth[0] == '7') selected @endif>7th</option>
                                                <option value="8" @if($fourth[0] == '8') selected @endif>8th</option>
                                                <option value="9" @if($fourth[0] == '9') selected @endif>9th</option>
                                                <option value="10" @if($fourth[0] == '10') selected @endif>10th</option>
                                                <option value="11" @if($fourth[0] == '11') selected @endif>11th</option>
                                                <option value="12" @if($fourth[0] == '12') selected @endif>12th</option>
                                                <option value="13" @if($fourth[0] == '13') selected @endif>13th</option>
                                                <option value="14" @if($fourth[0] == '14') selected @endif>14th</option>
                                                <option value="15" @if($fourth[0] == '15') selected @endif>15th</option>
                                                <option value="16" @if($fourth[0] == '16') selected @endif>16th</option>
                                                <option value="17" @if($fourth[0] == '17') selected @endif>17th</option>
                                                <option value="18" @if($fourth[0] == '18') selected @endif>18th</option>
                                                <option value="19" @if($fourth[0] == '19') selected @endif>19th</option>
                                                <option value="20" @if($fourth[0] == '20') selected @endif>20th</option>
                                                <option value="21" @if($fourth[0] == '21') selected @endif>21st</option>
                                                <option value="22" @if($fourth[0] == '22') selected @endif>22nd</option>
                                                <option value="23" @if($fourth[0] == '23') selected @endif>23rd</option>
                                                <option value="24" @if($fourth[0] == '24') selected @endif>24th</option>
                                                <option value="25" @if($fourth[0] == '25') selected @endif>25th</option>
                                                <option value="26" @if($fourth[0] == '26') selected @endif>26th</option>
                                                <option value="27" @if($fourth[0] == '27') selected @endif>27th</option>
                                                <option value="28" @if($fourth[0] == '28') selected @endif>28th</option>
                                                <option value="last day" @if($fourth[0] == 'last day') selected @endif>Last Day</option>
                                            </select>
                                        </div>
                                        <label class="pt-1 frequency-fields">of</label>
                                        <div class="form-group col-md-3 col-6 frequency-fields">
                                            <select class="form-control custom-select" name="fourth_month" id="quarterly_fourth_month" onchange="payDates();">
                                                <option value="10" @if($fourth[1] == '10') selected @endif>October</option>
                                                <option value="11" @if($fourth[1] == '11') selected @endif>November</option>
                                                <option value="12" @if($fourth[1] == '12') selected @endif>December</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                                
                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label">Pay days are... <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group col-md-2 col-6">
                                    <select class="form-control custom-select" name="pay_days" id="pay_days" onchange="payDates();">
                                        <option value="0" @if(old('pay_days', $paySchedule->pay_days) == '0') selected @endif>0</option>
                                        <option value="1" @if(old('pay_days', $paySchedule->pay_days) == '1') selected @endif>1</option>
                                        <option value="2" @if(old('pay_days', $paySchedule->pay_days) == '2') selected @endif>2</option>
                                        <option value="3" @if(old('pay_days', $paySchedule->pay_days) == '3') selected @endif>3</option>
                                        <option value="4" @if(old('pay_days', $paySchedule->pay_days) == '4') selected @endif>4</option>
                                        <option value="5" @if(old('pay_days', $paySchedule->pay_days) == '5') selected @endif>5</option>
                                        <option value="6" @if(old('pay_days', $paySchedule->pay_days) == '6') selected @endif>6</option>
                                        <option value="7" @if(old('pay_days', $paySchedule->pay_days) == '7') selected @endif>7</option>
                                        <option value="8" @if(old('pay_days', $paySchedule->pay_days) == '8') selected @endif>8</option>
                                        <option value="9" @if(old('pay_days', $paySchedule->pay_days) == '9') selected @endif>9</option>
                                        <option value="10" @if(old('pay_days', $paySchedule->pay_days) == '10') selected @endif>10</option>
                                        <option value="11" @if(old('pay_days', $paySchedule->pay_days) == '11') selected @endif>11</option>
                                        <option value="12" @if(old('pay_days', $paySchedule->pay_days) == '12') selected @endif>12</option>
                                        <option value="13" @if(old('pay_days', $paySchedule->pay_days) == '13') selected @endif>13</option>
                                        <option value="14" @if(old('pay_days', $paySchedule->pay_days) == '14') selected @endif>14</option>
                                        <option value="15" @if(old('pay_days', $paySchedule->pay_days) == '15') selected @endif>15</option>
                                    </select>
                                </div>
                                <label class="pt-1">day(s) after the period ends</label>
                            </div>
                                
                            <div class="row">
                                <div class="form-group col-6">
                                    <label class="control-label">If a pay day lands on a weekend or federal holiday? <span class="text-danger">*</span></label>
                                    <select class="form-control custom-select" name="exceptional_pay_day" id="exceptional_pay_day" onchange="payDates();">
                                        <option value="Before" @if(old('exceptional_pay_day', $paySchedule->exceptional_pay_day) == 'Before') selected @endif>Pay on the previous business day</option>
                                        <option value="After" @if(old('exceptional_pay_day', $paySchedule->exceptional_pay_day) == 'After') selected @endif>Pay on the next business day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Update Pay Schedule"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Update')}}</span></button>
                            <button type="button" class="btn btn-info waves-effect waves-float waves-light" onclick='$("#dates_preview").modal("show");' data-toggle="tooltip" data-placement="top" data-original-title="Dates Preview"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Preview')}}</span></button>
                            <button type="button" class="btn btn-outline-warning waves-effect waves-float waves-light" onclick="window.location.href='@if(isset($locale)){{route('pay-schedule.index', [$locale])}} @else {{route('pay-schedule.index', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Cancel"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                        </div>
                    </div>

                    <div class="modal modal-slide-in fade" id="dates_preview" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document" style="width:700px;">
                            <div class="modal-content pt-0">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="myModalLabel1">Pay Schedule Dates Preview</h4>
                                </div>
                                <div class="modal-body mt-1 mb-1" style="max-height:700px; overflow: auto;">
                                    <div class="card-datatable table-responsive pt-0 p-1">
                                        <table id="dates-preview-table" class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Period Start</th>
                                                    <th>Period End</th>
                                                    <th>Pay Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($paySchedule['payScheduleDates'] as $date)
                                                    <tr>
                                                        <td>{{$date->period_start}}</td>
                                                        <td>{{$date->period_end}}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <div class="col-9 p-0 m-0">
                                                                    {{$date->pay_date}} 
                                                                </div>
                                                                <div class="col-2 p-0 m-0">
                                                                    @if($date->adjustment == "true")
                                                                        <i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather='info'></i>
                                                                    @endif
                                                                    @if($date->adjustment == "manual")
                                                                        <i data-toggle="tooltip" data-original-title="Pay date adjusted manually" data-feather='info'></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('vendor-script')
    <!-- vendor files -->
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
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-pay-schedule-validation.js')) }}"></script>
    <script src="{{ asset('js/scripts/pay-schedule-edit-script.js') }}"></script>
@endsection