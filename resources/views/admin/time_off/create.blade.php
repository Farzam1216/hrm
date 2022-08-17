@extends('layouts.contentLayoutMaster')
@section('title','Create Policy')
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
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('policy.index', [$locale])}} @else {{route('policy.index', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Back"><i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-none"> {{__('language.Back')}}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-1">
                <form id="time-off-policy-form" action="@if(isset($locale)) {{route('policy.store', [$locale])}} @else {{route('policy.store', ['en'])}} @endif" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="control-label">{{__('language.Time Off Type')}}<span class="text-danger">*</span></label>
                            <select class="form-control custom-select" data-placeholder="Choose a Selected Time Off Type" name="time_off_type">
                                <option value="">Select</option>
                                @foreach($TimeOffTypes as $TimeOffType)
                                    <option value="{{$TimeOffType->id}}">{{$TimeOffType->time_off_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label class="control-label">{{__('language.Policy Name')}}<span class="text-danger">*</span></label>
                            <input type="text" name="policy_name" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Policy Name')}}">
                        </div>
                        <div class="form-group col-6">
                            <label class="control-label">{{__('language.Policy Type')}}</label>
                            <select class="form-control custom-select" placeholder="Select Policy Type" name="policy_type">
                                <option value="">Select</option>
                                    <option value="default probationary">Default Probationary</option>
                                    <option value="default permanent">Default Permanent</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    @php
                    $level=1;
                    @endphp
                    <div class="levels">
                        <div class="level-section current" level="{{$level}}">
                            <h3>{{__('language.Level').'-'.$level}}</h3>
                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label">{{__('language.Start')}}</label>
                                </div>
                                <div class="form-group col-md-2 col-4">
                                    <input type="text" name="level[{{$level}}][accrual-start]" class="form-control">
                                </div>
                                <div class="form-group col-md-4 col-4">
                                    <select class="form-control custom-select" name="level[{{$level}}][start-type]">
                                        <option value="Days">Days</option>
                                        <option value="Weeks">Weeks</option>
                                        <option value="Months">Months</option>
                                        <option value="Years">Years</option>
                                    </select>
                                </div>
                                <label class="control-label col-4">Starts after hire Date</label>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">{{__('language.Amount Accrued')}}</label>
                                </div>
                                <div class="form-group col-md-2 col-6">
                                    <input type="text" name="level[{{$level}}][accrual-hours]" class="form-control">
                                </div>
                                <div class="form-group col-md-4 col-6 accrual-type{{$level}}">
                                    <select class="form-control custom-select accrual-type-select"
                                        level="{{$level}}" name="level[{{$level}}][accrual-type]">
                                        <option value="Daily">Daily</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Every other week">Every other week</option>
                                        <option value="Twice a month">Twice a month</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Twice a Yearly">Twice a Yearly</option>
                                        <option value="Quarterly">Quarterly</option>
                                        <option value="Yearly">Yearly</option>
                                        <option value="Anniversary">Anniversary</option>
                                        <option value="Per hour worked">Per hour worked</option>
                                    </select>
                                </div>
                            </div>
                                
                            <div class="row">
                                <div class="form-group col-md-2 col-6">
                                    <label class="control-label">{{__('language.Max Accrual')}}</label>
                                    <input type="text" name="level[{{$level}}][max-accrual]" class="form-control">
                                </div>
                                <div class="form-group col-md-4 col-6 carryover-type{{$level}}">
                                    <label class="control-label">{{__('language.Carryover Amount')}}</label>
                                    <select class="form-control custom-select carryover-type-select"
                                        level="{{$level}}" name="level[{{$level}}][carryover-type]">
                                        <option value="0">None</option>
                                        <option value="1">Upto</option>
                                        <option value="2">Unlimited</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="add-level">
                        <button type="button" id="add-level" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Add Level"><i data-feather="plus"></i><span class="d-none d-lg-inline d-md-inline d-sm-none">Add Level</span></button>
                    </div>
                    <hr>
                    <h3>Accrual Options</h3>
                    <div class="row">
                        <div class="form-group col-6 first-accrual">
                            <label class="control-label">{{__('language.First Accrual')}}</label>
                            <select class="form-control custom-select" name="first-accrual">
                                <option value="Prorate">Prorate</option>
                                <option value="Full Amount">Full Amount</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label class="control-label">{{__('language.Accruals Happen')}}</label>
                            <select class="form-control custom-select" name="carryover-happen">
                                <option value="At the end of period">At the end of period</option>
                                <option value="At the begining of the period">At the begining of the period
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12">
                        <div class="carryover-date-option row">
                            <div class="form-group col-6 carryover-date-select">
                                <label class="control-label">{{__('language.Carryover Date')}}</label>
                                <select class="form-control custom-select carryover-date" name="carryover-date">
                                    <option value="1st January">1st January</option>
                                    <option value="employee_hire_date">Employee hire date</option>
                                    <option value="Other">Other...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Add Time Off"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Add')}} {{__('language.Time off')}}</span></button>
                                <button type="button" class="btn btn-outline-warning waves-effect waves-float waves-light" onclick="window.location.href='@if(isset($locale)){{route('policy.index', [$locale])}} @else {{route('policy.index', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Cancel"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        function accrualWeek(week_day) {
            var monthWeek = [[], [], [], []];
            // if we haven't yet passed the day of the week that I need:
            if (moment().isoWeekday() <= moment().day(week_day).format('d')) {
                // then just give me this week's instance of that day
                var week = moment().isoWeekday(week_day).startOf('day');
                var dayOfMonth = Math.ceil(week.date() / 7);
                if (dayOfMonth == 1 || dayOfMonth == 3 || dayOfMonth == 5) {
                    monthWeek[0]['firstWeek'] = week.week() % 2;
                    monthWeek[1]['firstWeek'] = week.format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(2, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(4, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    monthWeek[0]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).week() % 2;
                    monthWeek[1]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(3, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    return monthWeek;
                } else {
                    monthWeek[0]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).week() % 2;
                    monthWeek[1]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(3, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    monthWeek[0]['firstWeek'] = week.week() % 2;
                    monthWeek[1]['firstWeek'] = week.format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(2, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(4, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    return monthWeek;
                }
            } else {
                // otherwise, give me next week's instance of that day
                var week = moment().add(1, 'week').isoWeekday(week_day).startOf('day');
                var dayOfMonth = Math.ceil(week.date() / 7);
                if (dayOfMonth == 1 || dayOfMonth == 3 || dayOfMonth == 5) {
                    monthWeek[0]['firstWeek'] = week.week() % 2;
                    ;
                    monthWeek[1]['firstWeek'] = week.format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek']+ ',' + moment().add(3, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    monthWeek[0]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).week() % 2;
                    ;
                    monthWeek[1]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] +',' + moment().add(4, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] =monthWeek[1]['secondWeek']+ ',' + moment().add(6, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    return monthWeek;

                } else {
                    monthWeek[0]['firstWeek'] = week.week() % 2;
                    monthWeek[1]['firstWeek'] = week.format('MMM D');
                    monthWeek[1]['firstWeek'] =monthWeek[1]['firstWeek'] +',' + moment().add(3, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('MMM D') +'...';
                    monthWeek[0]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).week() % 2;
                    monthWeek[1]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(4, 'weeks').isoWeekday(week_day).format('MMM D');
                    monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(6, 'weeks').isoWeekday(week_day).format('MMM D') + '...';
                    return monthWeek;

                }
            }
        }

        $(document).on('change','.accrual-type-select',function () {
            var level =$('.level-section.current').attr('level');
            var start_type = $( "option:selected",this).text();
            start_type = $.trim(start_type);
            var type_select_level = $(this).attr('level');
            if(start_type == 'Weekly'){
                var first_accrual_day = "level["+type_select_level+"][first-accrual-day]";
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                $('.accrual-type'+type_select_level).after(' <div class="form-group col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+first_accrual_day+'>\n' +
                    '                                        <option value="">Select Day</option>\n' +
                    '                                        <option value="Sun">Sun</option>\n' +
                    '                                        <option value="Mon">Mon</option>\n' +
                    '                                        <option value="Tue">Tue</option>\n' +
                    '                                        <option value="Wed">Wed</option>\n' +
                    '                                        <option value="Thur">Thur</option>\n' +
                    '                                        <option value="Fri">Fri</option>\n' +
                    '                                        <option value="Sat">Sat</option>\n' +
                    '                                    </select>\n' +
                    '                                </div>');
            }
            else if(start_type == 'Every other week'){
                            var accrual_day = "level[" + type_select_level + "][accrual-day]";
                            var week_day = "Friday";
                            var accrual_weeks = "level[" + type_select_level + "][accrual-week]";
                            var monthWeek = [[], [], [], []];
                            monthWeek = accrualWeek(week_day);
                            $('.accrual-type-fields' + type_select_level).remove();
                            $('.accrual-frequency-fields' + type_select_level).remove();
                            $('.accrual-type' + type_select_level).after('<div class="form-group accrual-type-fields' + type_select_level + ' col-md-2 col-5">\n' + 
                                '                                       <select class="form-control custom-select accrual-week-day" name=' + accrual_day + '>\n' +
                                '                                           <option value="Sunday">Sunday</option>\n' +
                                '                                           <option value="Monday">Monday</option>\n' +
                                '                                           <option value="Tuesday">Tuesday</option>\n' +
                                '                                           <option value="Wednesday">Wednesday</option>\n' +
                                '                                           <option value="Thursday">Thursday</option>\n' +
                                '                                           <option value="Friday" selected>Friday</option>\n' +
                                '                                           <option value="Saturday">Saturday</option>\n' +
                                '                                       </select>\n' +
                                '                                   </div>\n' +
                                '                                   <label class="col-md-1 col-2 pr-0 mr-0 accrual-type-fields' + type_select_level + '" id="Weekday">Which ' + week_day + 's?</label>\n' +
                                '                                   <div class="form-group m-0 accrual-type-fields' + type_select_level + ' col-md-3 col-5">\n' +
                                '                                       <select class="form-control custom-select accrual-week" id="Weekdays" name=' + accrual_weeks + '>\n' +
                                '                                           <option value=' + monthWeek[0]['firstWeek']+'>' + monthWeek[1]['firstWeek'] + '</option>\n' +
                                '                                           <option value=' + monthWeek[0]['secondWeek'] + '>' + monthWeek[1]['secondWeek'] + '</option>\n' +
                                '                                       </select>\n' +
                                '                                   </div>');
            }
            else if(start_type == 'Twice a month'){
                var accrual_firstdate = "level["+type_select_level+"][accrual-firstdate]";
                var accrual_lastdate = "level["+type_select_level+"][accrual-lastdate]";
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                $('.accrual-type'+type_select_level).after('<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select First Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                    </select>\n' +
                    '                                </div><div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_lastdate+'>\n' +
                    '                                        <option value="">Select Second Day</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                                </div>');
            }
            else if(start_type == 'Monthly'){
                var accrual_firstdatemonthly = "level["+type_select_level+"][accrual-firstdatemonthly]";
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                $('.accrual-type'+type_select_level).after('<div class="form-group col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdatemonthly+'>\n' +
                    '                                        <option value="">Select Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Twice a Yearly'){
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                var accrual_firstdate = "level["+type_select_level+"][accrual-firstdate]";
                var accrual_firstMonth = "level["+type_select_level+"][accrual-firstMonth]";
                var accrual_seconddate = "level["+type_select_level+"][accrual-seconddate]";
                var accrual_secondMonth = "level["+type_select_level+"][accrual-secondMonth]";
                $('.accrual-type'+type_select_level).after('<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select First Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstMonth+'>\n' +
                    '                                        <option value="">Select First Month</option>\n' +
                    '                                        <option value="1">January</option>\n' +
                    '                                        <option value="2">February</option>\n' +
                    '                                        <option value="3">March</option>\n' +
                    '                                        <option value="4">April</option>\n' +
                    '                                        <option value="5">May</option>\n' +
                    '                                        <option value="6">June</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_seconddate+'>\n' +
                    '                                        <option value="">Select Second Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_secondMonth+'>\n' +
                    '                                        <option value="">Select Second Month</option>\n' +
                    '                                        <option value="7">July</option>\n' +
                    '                                        <option value="8">August</option>\n' +
                    '                                        <option value="9">September</option>\n' +
                    '                                        <option value="10">October</option>\n' +
                    '                                        <option value="11">November</option>\n' +
                    '                                        <option value="12">December</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Yearly'){
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                var accrual_firstdate = "level["+type_select_level+"][accrual-firstdate]";
                var accrual_firstMonth = "level["+type_select_level+"][accrual-firstMonth]";
                $('.accrual-type'+type_select_level).after('<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstMonth+'>\n' +
                    '                                        <option value="">Select Month</option>\n' +
                    '                                        <option value="1">January</option>\n' +
                    '                                        <option value="2">February</option>\n' +
                    '                                        <option value="3">March</option>\n' +
                    '                                        <option value="4">April</option>\n' +
                    '                                        <option value="5">May</option>\n' +
                    '                                        <option value="6">June</option>\n' +
                    '                                        <option value="7">July</option>\n' +
                    '                                        <option value="8">August</option>\n' +
                    '                                        <option value="9">September</option>\n' +
                    '                                        <option value="10">October</option>\n' +
                    '                                        <option value="11">November</option>\n' +
                    '                                        <option value="12">December</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Quarterly'){
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                var accrual_firstdate = "level["+type_select_level+"][accrual-firstdate]";
                var accrual_firstMonth = "level["+type_select_level+"][accrual-firstMonth]";
                var accrual_seconddate = "level["+type_select_level+"][accrual-seconddate]";
                var accrual_secondMonth = "level["+type_select_level+"][accrual-secondMonth]";
                var accrual_thirddate = "level["+type_select_level+"][accrual-thirddate]";
                var accrual_thirdMonth = "level["+type_select_level+"][accrual-thirdMonth]";
                var accrual_fourthdate = "level["+type_select_level+"][accrual-fourthdate]";
                var accrual_fourthMonth = "level["+type_select_level+"][accrual-fourthMonth]";
                $('.accrual-type'+type_select_level).after('<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select First Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstMonth+'>\n' +
                    '                                        <option value="">Select First Month</option>\n' +
                    '                                        <option value="1">January</option>\n' +
                    '                                        <option value="2">February</option>\n' +
                    '                                        <option value="3">March</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_seconddate+'>\n' +
                    '                                        <option value="">Select Second Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_secondMonth+'>\n' +
                    '                                        <option value="">Select Second Month</option>\n' +
                    '                                        <option value="4">April</option>\n' +
                    '                                        <option value="5">May</option>\n' +
                    '                                        <option value="6">June</option>\n' +

                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_thirddate+'>\n' +
                    '                                        <option value="">Select Third Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_thirdMonth+'>\n' +
                    '                                        <option value="">Select Third Month</option>\n' +
                    '                                        <option value="7">July</option>\n' +
                    '                                        <option value="8">August</option>\n' +
                    '                                        <option value="9">September</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_fourthdate+'>\n' +
                    '                                        <option value="">Select Fourth Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-type-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_fourthMonth+'>\n' +
                    '                                        <option value="">Select Fourth Month</option>\n' +
                    '                                        <option value="10">October</option>\n' +
                    '                                        <option value="11">November</option>\n' +
                    '                                        <option value="12">December</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Per hour worked'){
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
                var Accrual_Frequency = "level["+type_select_level+"][Accrual-Frequency]";
                $('.accrual-type'+type_select_level).after('<div class="form-group col-6 accrual-type-fields'+type_select_level+' Accrual-Frequency-fields'+type_select_level+'">\n' +
                    '                                    <select class="form-control custom-select" level="'+type_select_level+'" id="testing" name='+Accrual_Frequency+'>\n' +
                    '                                        <option value="">Select Accruing</option>\n' +
                    '                                        <option value="Daily">Daily</option>\n' +
                    '                                        <option value="Weekly">Weekly</option>\n' +
                    '                                        <option value="Every Other Week">Every Other Week</option>\n' +
                    '                                        <option value="Twice a month">Twice a month</option>\n' +
                    '                                        <option value="Monthly">Monthly</option>\n' +
                    '                                        <option value="Twice a Yearly">Twice a Yearly</option>\n' +
                    '                                        <option value="Quarterly">Quarterly</option>\n' +
                    '                                        <option value="Yearly">Yearly</option>\n' +
                    '                                        <option value="Anniversary">Anniversary</option>\n' +
                    '                                    </select>\n' +
                    '                                </div>');
            }
            else{
                $('.accrual-type-fields'+type_select_level).remove();
                $('.accrual-frequency-fields'+type_select_level).remove();
            }
        });
        //   jQuery(document).on('change', 'select[name=Accrual-Frequency]', function() {
        $(document).on('change', '#testing', function() {
            var frequency_fields_level = $(this).attr('level');
            var start_type = $('#testing :selected').text();
            if(start_type == 'Weekly'){
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
                var first_accrual_day = "level["+frequency_fields_level+"][first-accrual-day]";
                $('.Accrual-Frequency-fields'+frequency_fields_level).after('<div class="form-group col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                   <select class="form-control custom-select" name='+first_accrual_day+'>\n' +
                    '                                        <option value="">Select Day</option>\n' +
                    '                                        <option value="Sun">Sun</option>\n' +
                    '                                        <option value="Mon">Mon</option>\n' +
                    '                                        <option value="Tue">Tue</option>\n' +
                    '                                        <option value="Wed">Wed</option>\n' +
                    '                                        <option value="Thur">Thur</option>\n' +
                    '                                        <option value="Fri">Fri</option>\n' +
                    '                                        <option value="Sat">Sat</option>\n' +
                    '                                    </select>\n' +
                    '                                </div>');
            }
            else if(start_type == 'Every Other Week'){
                            $('.accrual-frequency-fields' + frequency_fields_level).remove();
                            var accrual_day = "level[" + frequency_fields_level + "][accrual-day]";
                            var accrual_weeks = "level[" + frequency_fields_level + "][accrual-week]";
                            var week_day = "Fridays";
                            var monthWeek = [[], [], [], []];
                            monthWeek = accrualWeek(week_day);
                            $('.Accrual-Frequency-fields' + frequency_fields_level).after('<div class="col-12"><div class="row"><div class="form-group accrual-frequency-fields' + frequency_fields_level + ' col-5">\n' +
                                '                                    <select class="form-control custom-select accrual-week-day" name=' + accrual_day + '>\n' +
                                '                                        <option value="Sunday">Sunday</option>\n' +
                                '                                        <option value="Monday">Monday</option>\n' +
                                '                                        <option value="Tuesday">Tuesday</option>\n' +
                                '                                        <option value="Wednesday">Wednesday</option>\n' +
                                '                                        <option value="Thursday">Thursday</option>\n' +
                                '                                        <option value="Friday" selected>Friday</option>\n' +
                                '                                        <option value="Saturday">Saturday</option>\n' +
                                '                                    </select>\n' +
                                '                                </div>\n' +
                                '                                       <label class="control-label col-md-1 col-2 pr-0 mr-0 accrual-frequency-fields' + frequency_fields_level + '" id="Weekday">Which ' + week_day + '?</label>\n' +
                                '                                    <div class="form-group accrual-frequency-fields' + frequency_fields_level + ' col-md-6 col-5">\n' +
                                '                                       <select class="form-control custom-select accrual-week" id="Weekdays" name=' + accrual_weeks + '>\n' +
                                '                                        <option value=' + monthWeek[0]['firstWeek']+'>' + monthWeek[1]['firstWeek'] + '</option>\n' +
                                '                                        <option value=' + monthWeek[0]['secondWeek'] + '>' + monthWeek[1]['secondWeek'] + '</option>\n' +
                                '                                    </select>\n' +
                                '                                </div></div></div>');
            }
            else if(start_type == 'Twice a month'){
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
                var accrual_firstdate = "level["+frequency_fields_level+"][accrual-firstdate]";
                var accrual_lastdate = "level["+frequency_fields_level+"][accrual-lastdate]";
                $('.Accrual-Frequency-fields'+frequency_fields_level).after('<div class="form-group col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select First Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                    </select>\n' +
                    '                                </div>\n' +
                    '<div class="form-group col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_lastdate+'>\n' +
                    '                                        <option value="">Select Second Day</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                                </div>');
            }
            else if(start_type == 'Monthly'){
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
                var accrual_firstdatemonthly = "level["+frequency_fields_level+"][accrual-firstdatemonthly]";
                $('.Accrual-Frequency-fields'+frequency_fields_level).after('<div class="form-group col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdatemonthly+'>\n' +
                    '                                        <option value="">Select Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Twice a Yearly'){
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
                var accrual_firstdate = "level["+frequency_fields_level+"][accrual-firstdate]";
                var accrual_firstMonth = "level["+frequency_fields_level+"][accrual-firstMonth]";
                var accrual_seconddate = "level["+frequency_fields_level+"][accrual-seconddate]";
                var accrual_secondMonth = "level["+frequency_fields_level+"][accrual-secondMonth]";
                $('.Accrual-Frequency-fields'+frequency_fields_level).after('<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select First Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstMonth+'>\n' +
                    '                                        <option value="">Select First Month</option>\n' +
                    '                                        <option value="1">January</option>\n' +
                    '                                        <option value="2">February</option>\n' +
                    '                                        <option value="3">March</option>\n' +
                    '                                        <option value="4">April</option>\n' +
                    '                                        <option value="5">May</option>\n' +
                    '                                        <option value="6">June</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_seconddate+'>\n' +
                    '                                        <option value="">Select Second Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_secondMonth+'>\n' +
                    '                                        <option value="">Select Second Month</option>\n' +
                    '                                        <option value="7">July</option>\n' +
                    '                                        <option value="8">August</option>\n' +
                    '                                        <option value="9">September</option>\n' +
                    '                                        <option value="10">October</option>\n' +
                    '                                        <option value="11">November</option>\n' +
                    '                                        <option value="12">December</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Yearly'){
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
                var accrual_firstdate = "level["+frequency_fields_level+"][accrual-firstdate]";
                var accrual_firstMonth = "level["+frequency_fields_level+"][accrual-firstMonth]";
                $('.Accrual-Frequency-fields'+frequency_fields_level).after('<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstMonth+'>\n' +
                    '                                        <option value="">Select Month</option>\n' +
                    '                                        <option value="1">January</option>\n' +
                    '                                        <option value="2">February</option>\n' +
                    '                                        <option value="3">March</option>\n' +
                    '                                        <option value="4">April</option>\n' +
                    '                                        <option value="5">May</option>\n' +
                    '                                        <option value="6">June</option>\n' +
                    '                                        <option value="7">July</option>\n' +
                    '                                        <option value="8">August</option>\n' +
                    '                                        <option value="9">September</option>\n' +
                    '                                        <option value="10">October</option>\n' +
                    '                                        <option value="11">November</option>\n' +
                    '                                        <option value="12">December</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else if(start_type == 'Quarterly'){
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
                var accrual_firstdate = "level["+frequency_fields_level+"][accrual-firstdate]";
                var accrual_firstMonth = "level["+frequency_fields_level+"][accrual-firstMonth]";
                var accrual_seconddate = "level["+frequency_fields_level+"][accrual-seconddate]";
                var accrual_secondMonth = "level["+frequency_fields_level+"][accrual-secondMonth]";
                var accrual_thirddate = "level["+frequency_fields_level+"][accrual-thirddate]";
                var accrual_thirdMonth = "level["+frequency_fields_level+"][accrual-thirdMonth]";
                var accrual_fourthdate = "level["+frequency_fields_level+"][accrual-fourthdate]";
                var accrual_fourthMonth = "level["+frequency_fields_level+"][accrual-fourthMonth]";
                $('.Accrual-Frequency-fields'+frequency_fields_level).after('<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstdate+'>\n' +
                    '                                        <option value="">Select First Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_firstMonth+'>\n' +
                    '                                        <option value="">Select First Month</option>\n' +
                    '                                        <option value="1">January</option>\n' +
                    '                                        <option value="2">February</option>\n' +
                    '                                        <option value="3">March</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_seconddate+'>\n' +
                    '                                        <option value="">Select Second Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_secondMonth+'>\n' +
                    '                                        <option value="">Select Second Month</option>\n' +
                    '                                        <option value="4">April</option>\n' +
                    '                                        <option value="5">May</option>\n' +
                    '                                        <option value="6">June</option>\n' +

                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_thirddate+'>\n' +
                    '                                        <option value="">Select Third Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_thirdMonth+'>\n' +
                    '                                        <option value="">Select Third Month</option>\n' +
                    '                                        <option value="7">July</option>\n' +
                    '                                        <option value="8">August</option>\n' +
                    '                                        <option value="9">September</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_fourthdate+'>\n' +
                    '                                        <option value="">Select Fourth Day</option>\n' +
                    '                                        <option value="1">1st</option>\n' +
                    '                                        <option value="2">2nd</option>\n' +
                    '                                        <option value="3">3rd</option>\n' +
                    '                                        <option value="4">4th</option>\n' +
                    '                                        <option value="5">5th</option>\n' +
                    '                                        <option value="6">6th</option>\n' +
                    '                                        <option value="7">7th</option>\n' +
                    '                                        <option value="8">8th</option>\n' +
                    '                                        <option value="9">9th</option>\n' +
                    '                                        <option value="10">10th</option>\n' +
                    '                                        <option value="11">11th</option>\n' +
                    '                                        <option value="12">12th</option>\n' +
                    '                                        <option value="13">13th</option>\n' +
                    '                                        <option value="14">14th</option>\n' +
                    '                                        <option value="15">15th</option>\n' +
                    '                                        <option value="16">16th</option>\n' +
                    '                                        <option value="17">17th</option>\n' +
                    '                                        <option value="18">18th</option>\n' +
                    '                                        <option value="19">19th</option>\n' +
                    '                                        <option value="20">20th</option>\n' +
                    '                                        <option value="21">21st</option>\n' +
                    '                                        <option value="22">22nd</option>\n' +
                    '                                        <option value="23">23rd</option>\n' +
                    '                                        <option value="24">24th</option>\n' +
                    '                                        <option value="25">25th</option>\n' +
                    '                                        <option value="26">26th</option>\n' +
                    '                                        <option value="27">27th</option>\n' +
                    '                                        <option value="28">28th</option>\n' +
                    '                                        <option value="29">29th</option>\n' +
                    '                                        <option value="30">30th</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>' +
                    '<div class="form-group col-md-3 col-6 accrual-frequency-fields'+frequency_fields_level+'">\n' +
                    '                                    <select class="form-control custom-select" name='+accrual_fourthMonth+'>\n' +
                    '                                        <option value="">Select Fourth Month</option>\n' +
                    '                                        <option value="10">October</option>\n' +
                    '                                        <option value="11">November</option>\n' +
                    '                                        <option value="12">December</option>\n' +
                    '                                    </select>\n' +
                    '                               </div>');
            }
            else{
                $('.accrual-frequency-fields'+frequency_fields_level).remove();
            }
        });
        $('.add-level').on('click', function () {
        var prev_level = $('.level-section.current').attr('level');
        var next_level = parseInt(prev_level) + 1;
        var accrual_type_select = "level[" + next_level + "][accrual-type]";
        var start_type = "level[" + next_level + "][start-type]";
        var accrual_hours = "level[" + next_level + "][accrual-hours]";
        var carryover_type = "level[" + next_level + "][carryover-type]";//Work
        var accrual_start = "level[" + next_level + "][accrual-start]";
        var max_accrual = "level[" + next_level + "][max-accrual]";
        $('.level-section.current').removeClass('current');
        $('.levels').append('<div class="level-section current" level="' + next_level + '">\n' +
            '                                <hr><div class="level control-label d-flex justify-content-between"><h3 style="display: inline-block;">Level-' + next_level + '</h3><a class="remove-level btn btn-outline-danger waves-effect" href="javascript:void(0)" title="Remove level"><i class="fas fa-trash-alt"></i> <span class="d-none d-lg-inline d-md-inline d-sm-none">Remove level</a></div>\n' +
            '                                <div class="row">\n' +
            '                                    <div class="form-group col-md-2 col-6">\n' +
            '                                    <label class="control-label">Start</label>\n' +
            '                                        <input type="text" name=' + accrual_start + ' class="form-control">\n' +
            '                                    </div>\n' +
            '                                    <div class="form-group col-md-4 col-6">\n' +
            '                                    <label class="control-label">Starts after hire Date</label>\n' +
            '                                        <select class="form-control custom-select" name=' + start_type + '>\n' +
            '                                            <option value="Days">Days</option>\n' +
            '                                            <option value="Weeks">Weeks</option>\n' +
            '                                            <option value="Months">Months</option>\n' +
            '                                            <option value="Years">Years</option>\n' +
            '                                        </select>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="row">\n' +
            '                                    <div class="col-md-12">\n' +
            '                                       <label class="control-label">Amount Accrued</label>\n' +
            '                                    </div>\n' +
            '                                    <div class="form-group col-md-2 col-6">\n' +
            '                                        <input type="text" name=' + accrual_hours + ' class="form-control">\n' +
            '                                    </div>\n' +
            '                                    <div class="form-group col-md-4 col-6 accrual-type' + next_level + '">\n' +
            '                                        <select class="form-control custom-select accrual-type-select" level="' + next_level + '" name="' + accrual_type_select + '">\n' +
            '                                            <option value="Daily">Daily</option>\n' +
            '                                            <option value="Weekly">Weekly</option>\n' +
            '                                            <option value="Every other week">Every other week</option>\n' +
            '                                            <option value="Twice a month">Twice a month</option>\n' +
            '                                            <option value="Monthly">Monthly</option>\n' +
            '                                            <option value="Twice a Yearly">Twice a Yearly</option>\n' +
            '                                            <option value="Quarterly">Quarterly</option>\n' +
            '                                            <option value="Yearly">Yearly</option>\n' +
            '                                            <option value="Anniversary">Anniversary</option>\n' +
            '                                            <option value="Per hour worked">Per hour worked</option>\n' +
            '                                        </select>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="row">\n' +
            '                                    <div class="form-group col-md-2 col-6">\n' +
            '                                        <label class="control-label">Max Accrual</label>\n' +
            '                                        <input type="text" name=' + max_accrual + ' class="form-control">\n' +
            '                                    </div>\n' +
            '                                    <div class="form-group col-md-4 col-6 carryover-type' + next_level + '">\n' +
            '                                       <label class="control-label">Carryover Amount</label>\n' +
            '                                       <select class="form-control custom-select carryover-type-select" level="' + next_level + '" name="' + carryover_type + '">\n' +
            '                                           <option value="0">None</option>\n' +
            '                                           <option value="1">Upto</option>\n' +
            '                                           <option value="2">Unlimited</option>\n' +
            '                                       </select>\n' +
            '                                   </div>\n' +
            '                                </div>\n' +
            '                            </div>');
        });
        $(document).on('click','.remove-level',function () {
            var current_level = $('.level-section.current').attr('level');
            var new_level = parseInt(current_level) - 1;
            $('.level-section.current').remove();
            $('.level-section[level='+new_level+']').addClass('current');
        });
        //Change selected weeks on changing week day for every other week type.
        $(document).on('change', '.accrual-week-day', function () {
            var selected_week_day = $("option:selected", this).val();
            var Week = [[], [], [], []];
            Week = accrualWeek(selected_week_day);
            console.log(Week);
            $('#Weekday').empty();
            $('#Weekday').append('<span>Which ' + selected_week_day + 's?</span>');
            $('#Weekdays').empty();
            $('#Weekdays').append(' <option value=' + Week[0]['firstWeek'] + '>' + Week[1]['firstWeek'] + '</option>\n' +
                '                                        <option value=' + Week[0]['secondWeek'] + '>' + Week[1]['secondWeek'] +'</option>\n');
        });
         //Script for accrual options carryover Date start
        $(document).on('change','.carryover-date',function() {
            var carry_over_option = $("option:selected",this).val();          
            if(carry_over_option == 'Other'){
                $('.carryover-option-other').remove();
                $('.carryover-date-select').after(
                            '<div class="form-group col-md-3 col-6 carry-over-option-other">\n' +
                                '<label class="control-label">{{__("language.Day")}}</label>\n' +
                                '<select class="form-control custom-select" name="custom-carry-over-date">\n' +
                                    '<option value="01">1st</option>\n' +
                                    '<option value="02">2nd</option>\n' +
                                    '<option value="03">3rd</option>\n' +
                                    '<option value="04">4th</option>\n' +
                                    '<option value="05">5th</option>\n' +
                                    '<option value="06">6th</option>\n' +
                                    '<option value="07">7th</option>\n' +
                                    '<option value="08">8th</option>\n' +
                                    '<option value="09">9th</option>\n' +
                                    '<option value="10">10th</option>\n' +
                                    '<option value="11">11th</option>\n' +
                                    '<option value="12">12th</option>\n' +
                                    '<option value="13">13th</option>\n' +
                                    '<option value="14">14th</option>\n' +
                                    '<option value="15">15th</option>\n' +
                                    '<option value="16">16th</option>\n' +
                                    '<option value="17">17th</option>\n' +
                                    '<option value="18">18th</option>\n' +
                                    '<option value="19">19th</option>\n' +
                                    '<option value="20">20th</option>\n' +
                                    '<option value="21">21st</option>\n' +
                                    '<option value="22">22nd</option>\n' +
                                    '<option value="23">23rd</option>\n' +
                                    '<option value="24">24th</option>\n' +
                                    '<option value="25">25th</option>\n' +
                                    '<option value="26">26th</option>\n' +
                                    '<option value="27">27th</option>\n' +
                                    '<option value="28">28th</option>\n' +
                                    '<option value="29">29th</option>\n' +
                                    '<option value="30">30th</option>\n' +
                                    '<option value="31">31th</option>\n' +
                                '</select>\n' +
                            '</div>\n' +
                            '<div class="form-group col-md-3 col-6 carry-over-option-other">\n' +
                                '<label class="control-label">{{__("language.Month")}}</label>\n' +
                                '<select class="form-control custom-select" name="custom-carry-over-month">\n' +
                                    '<option value="01">January</option>\n' +
                                    '<option value="02">February</option>\n' +
                                    '<option value="03">March</option>\n' +
                                    '<option value="04">April</option>\n' +
                                    '<option value="05">May</option>\n' +
                                    '<option value="06">June</option>\n' +
                                    '<option value="07">July</option>\n' +
                                    '<option value="08">August</option>\n' +
                                    '<option value="09">September</option>\n' +
                                    '<option value="10">October</option>\n' +
                                    '<option value="11">November</option>\n' +
                                    '<option value="12">December</option>\n' +
                                '</select>\n' +
                            '</div>'                                  
                    );
                }else{
                    $('.carry-over-option-other').remove();
                }
            });
        //Script for carryover amount start
        $(document).on('change','.carryover-type-select',function() {
            var level = $(this).attr('level');
            var carry_over_value = $("option:selected",this).val();
            if(carry_over_value == '1'){
                $('.carry-amount-option-upto'+level).remove();
                $('.carryover-type'+level).after(  
                '<div class="form-group col-6 carry-amount-option-upto'+level+'">\n' +
                '    <label class="control-label">{{__("language.Amount Upto")}}<span class="text-danger">*</span></label>\n' +
                '    <input type="text" name="level['+level+'][carry-upto]" class="form-control" required>\n' +
                '</div>'                            
                );
            }else{
                $('.carry-amount-option-upto'+level).remove();
            }                       
            //remove carryover date when value 2 is selected                      
            // if (carry_over_value == '2') {
            //     var current_level = $('.level-section.current').attr('level');
            //     $('.carryover-date-option').remove();
            // }else{
            //     $('.carryover-date-option').remove();
            //     $('.first-accrual').after(
            //         //
            //     '       <div class="carryover-date-option">\n' +
            //     '           <div class="row">\n' +
            //     '               <label class="control-label col-md-12">{{__('language.Carryover Date')}}</label>\n' +
            //     '       </div>\n' +
            //     '       <div class="row">\n' +
            //     '          <div class="col-md-2  carryover-date-select">\n' +
            //     '              <select class="form-control custom-select carryover-date" name="carryover-date">\n' +
            //     '                  <option value="1st January">1st January</option>\n' +
            //     '                  <option value="employee_hire_date">Employee hire date</option>\n' +
            //     '                  <option value="Other">Other...</option>\n' +
            //     '              </select>\n' +
            //     '          </div>\n' + 
            //     '       </div>\n' +
            //     '     </div>'       
            //     );
            // }
        });
    });
</script>
@stop
@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/validations/form-time-off-policy-validation.js')) }}"></script>
@endsection