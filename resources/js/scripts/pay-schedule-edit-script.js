$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    
    $("#dates-preview-table").DataTable({
        "lengthMenu": [ [-1], ["All"] ],
        "ordering": false,
        "drawCallback": function( settings ) {
            feather.replace();
        }
    });

    var frequency = '{!! $paySchedule->frequency !!}';
    var everyOtherWeek = '{!! $paySchedule->period_ends !!}'.split('day,');
    if (frequency === 'Every other week') {
        var Week = [[], [], [], []];
        var day = everyOtherWeek[0];
        var recursion = everyOtherWeek[1];
        Week = accrualWeek(day);

        $('#Weekday').empty();
        $('#Weekday').append('<span>Which '+everyOtherWeek[0]+'\'s?</span>');
        $('#Weekdays').empty();
        $('#Weekdays').append(
            '<option value=' + Week[1]['firstWeek'] + ' id="first">' + Week[1]['firstWeek'] + '</option>\n' +
            '<option value=' + Week[1]['secondWeek'] + ' id="second">' + Week[1]['secondWeek'] +'</option>\n'
        );
        
        if (recursion == Week[1]['firstWeek']) {
            document.getElementById("first").setAttribute('selected', true);
        }
        if (recursion == Week[1]['secondWeek']) {
            document.getElementById("second").setAttribute('selected', true);
        }
    }

    function accrualWeek(week_day) {
        var monthWeek = [[], [], [], []];
        // if we haven't yet passed the day of the week that I need:
        if (moment().isoWeekday() <= moment().day(week_day).format('d')) {
            // then just give me this week's instance of that day
            var week = moment().isoWeekday(week_day).startOf('day');
            var dayOfMonth = Math.ceil(week.date() / 7);
            if (dayOfMonth == 1 || dayOfMonth == 3 || dayOfMonth == 5) {
                monthWeek[0]['firstWeek'] = week.week() % 2;
                monthWeek[1]['firstWeek'] = week.format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(2, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(4, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                monthWeek[0]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).week() % 2;
                monthWeek[1]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(3, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                return monthWeek;
            } else {
                monthWeek[0]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).week() % 2;
                monthWeek[1]['secondWeek'] = moment().add(1, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(3, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                monthWeek[0]['firstWeek'] = week.week() % 2;
                monthWeek[1]['firstWeek'] = week.format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(2, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(4, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                return monthWeek;
            }
        } else {
            // otherwise, give me next week's instance of that day
            var week = moment().add(1, 'week').isoWeekday(week_day).startOf('day');
            var dayOfMonth = Math.ceil(week.date() / 7);
            if (dayOfMonth == 1 || dayOfMonth == 3 || dayOfMonth == 5) {
                monthWeek[0]['firstWeek'] = week.week() % 2;
                ;
                monthWeek[1]['firstWeek'] = week.format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek']+ ',' + moment().add(3, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                monthWeek[0]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).week() % 2;
                ;
                monthWeek[1]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] +',' + moment().add(4, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] =monthWeek[1]['secondWeek']+ ',' + moment().add(6, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                return monthWeek;

            } else {
                monthWeek[0]['firstWeek'] = week.week() % 2;
                monthWeek[1]['firstWeek'] = week.format('D-MMM');
                monthWeek[1]['firstWeek'] =monthWeek[1]['firstWeek'] +',' + moment().add(3, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['firstWeek'] = monthWeek[1]['firstWeek'] + ',' + moment().add(5, 'weeks').isoWeekday(week_day).format('D-MMM') +'...';
                monthWeek[0]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).week() % 2;
                monthWeek[1]['secondWeek'] = moment().add(2, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(4, 'weeks').isoWeekday(week_day).format('D-MMM');
                monthWeek[1]['secondWeek'] = monthWeek[1]['secondWeek'] + ',' + moment().add(6, 'weeks').isoWeekday(week_day).format('D-MMM') + '...';
                return monthWeek;
            }
        }
    }

    $(document).on('change','.pay-schedule-frequency',function () {
        start_type = this.value;

        if(start_type == 'Weekly'){
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
            $('.frequency-type').after(' <div class="row">\n' +
                '                               <div class="col-12 frequency-fields"><label>Pay periods end on...<span class="text-danger">*</span></label></div>\n' +
                '                               <div class="form-group col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="week_day" id="weekly_week_day" onchange="payDates()">\n' +
                '                                        <option value="">Select Day</option>\n' +
                '                                        <option value="Sunday">Sunday</option>\n' +
                '                                        <option value="Monday">Monday</option>\n' +
                '                                        <option value="Tuesday">Tuesday</option>\n' +
                '                                        <option value="Wednesday">Wednesday</option>\n' +
                '                                        <option value="Thursday">Thursday</option>\n' +
                '                                        <option value="Friday">Friday</option>\n' +
                '                                        <option value="Saturday">Saturday</option>\n' +
                '                                    </select>\n' +
                '                                </div></div>');
        }
        else if(start_type == 'Every other week'){
                        var week_day = "Friday";
                        var monthWeek = [[], [], [], []];
                        monthWeek = accrualWeek(week_day);
                        $('.frequency-fields').remove();
                        $('.pay-frequency-fields').remove();
                        $('.frequency-type').after(' <div class="row">\n' +
                            '                                   <div class="form-group frequency-fields col-md-2 col-5 frequency-fields">\n'+
                            '                                       <label>Pay periods end on...<span class="text-danger">*</span></label>\n' +
                            '                                       <select class="form-control custom-select frequency-week-day" name="week_day" id="every_week_day" onchange="payDates()">\n' +
                            '                                           <option value="Sunday">Sunday</option>\n' +
                            '                                           <option value="Monday">Monday</option>\n' +
                            '                                           <option value="Tuesday">Tuesday</option>\n' +
                            '                                           <option value="Wednesday">Wednesday</option>\n' +
                            '                                           <option value="Thursday">Thursday</option>\n' +
                            '                                           <option value="Friday" selected>Friday</option>\n' +
                            '                                           <option value="Saturday">Saturday</option>\n' +
                            '                                       </select>\n' +
                            '                                   </div>\n' +
                            '                                   <div class="form-group m-0 frequency-fields col-md-3 col-5">\n' +
                            '                                       <label id="Weekday">Which ' + week_day + 's?</label>\n' +
                            '                                       <select class="form-control custom-select" id="Weekdays" name="dates" onchange="payDates()">\n' +
                            '                                           <option value='+monthWeek[1]['firstWeek']+'>' + monthWeek[1]['firstWeek'] + '</option>\n' +
                            '                                           <option value='+monthWeek[1]['secondWeek']+'>' + monthWeek[1]['secondWeek'] + '</option>\n' +
                            '                                       </select>\n' +
                            '                                   </div>');
        }
        else if(start_type == 'Twice a monthly'){
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
            $('.frequency-type').after('<div class="row">\n' +
                '                               <div class="col-12 frequency-fields"><label>Pay periods end on...<span class="text-danger">*</span></label></div>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="first_date" id="twice_month_first_date" onchange="payDates()">\n' +
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
                '                               <label class="pt-1 frequency-fields">and the</label>\n' +
                '                                <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="second_date" id="twice_month_second_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                                </div>\n' +
                '                               <label class="pt-1 frequency-fields">of each month</label>\n' +
                '                           </div>');
        }
        else if(start_type == 'Monthly'){
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
            $('.frequency-type').after('<div class="row">\n' +
                '                               <div class="col-12 frequency-fields"><label>Pay periods end on...<span class="text-danger">*</span></label></div>\n' +
                '                               <div class="form-group col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="date" id="monthly_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div></div>');
        }
        else if(start_type == 'Twice a yearly'){
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
            $('.frequency-type').after('<div class="row">\n' +
                '                               <div class="col-12 frequency-fields"><label>Pay periods end on...<span class="text-danger">*</span></label></div>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="first_date" id="twice_year_first_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="first_month" id="twice_year_first_month" onchange="payDates()">\n' +
                '                                        <option value="1">January</option>\n' +
                '                                        <option value="2">February</option>\n' +
                '                                        <option value="3">March</option>\n' +
                '                                        <option value="4">April</option>\n' +
                '                                        <option value="5">May</option>\n' +
                '                                        <option value="6">June</option>\n' +
                '                                    </select>\n' +
                '                               </div></div>' +
                '                               <div class="row"><div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="second_date" id="twice_year_second_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="second_month" id="twice_year_second_month" onchange="payDates();" onchange="payDates()">\n' +
                '                                        <option value="7">July</option>\n' +
                '                                        <option value="8">August</option>\n' +
                '                                        <option value="9">September</option>\n' +
                '                                        <option value="10">October</option>\n' +
                '                                        <option value="11">November</option>\n' +
                '                                        <option value="12">December</option>\n' +
                '                                    </select>\n' +
                '                               </div></div>');
        }
        else if(start_type == 'Yearly'){
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
            $('.frequency-type').after('<div class="row">\n' +
                '                               <div class="col-12 frequency-fields"><label>Pay periods end on...<span class="text-danger">*</span></label></div>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="date" id="yearly_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="month" id="yearly_month" onchange="payDates()">\n' +
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
                '                               </div></div>');
        }
        else if(start_type == 'Quarterly'){
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
            $('.frequency-type').after('<div class="row">\n' +
                '                               <div class="col-12 frequency-fields"><label>Pay periods end on...<span class="text-danger">*</span></label></div>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="first_date" id="quarterly_first_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>\n' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="first_month" id="quarterly_first_month" onchange="payDates()">\n' +
                '                                        <option value="1">January</option>\n' +
                '                                        <option value="2">February</option>\n' +
                '                                        <option value="3">March</option>\n' +
                '                                    </select>\n' +
                '                               </div></div>' +
                '                               <div class="row"><div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="second_date" id="quarterly_second_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="second_month" id="quarterly_second_month" onchange="payDates()">\n' +
                '                                        <option value="4">April</option>\n' +
                '                                        <option value="5">May</option>\n' +
                '                                        <option value="6">June</option>\n' +

                '                                    </select>\n' +
                '                               </div></div>' +
                '                               <div class="row"><div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="third_date" id="quarterly_third_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="third_month" id="quarterly_third_month" onchange="payDates()">\n' +
                '                                        <option value="7">July</option>\n' +
                '                                        <option value="8">August</option>\n' +
                '                                        <option value="9">September</option>\n' +
                '                                    </select>\n' +
                '                               </div></div>' +
                '                               <div class="row"><div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="fourth_date" id="quarterly_fourth_date" onchange="payDates()">\n' +
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
                '                                        <option value="last day">Last Day</option>\n' +
                '                                    </select>\n' +
                '                               </div>' +
                '                               <label class="pt-1 frequency-fields">of</label>\n' +
                '                               <div class="form-group col-md-3 col-6 frequency-fields">\n' +
                '                                    <select class="form-control custom-select" name="fourth_month" id="quarterly_fourth_month" onchange="payDates()">\n' +
                '                                        <option value="10">October</option>\n' +
                '                                        <option value="11">November</option>\n' +
                '                                        <option value="12">December</option>\n' +
                '                                    </select>\n' +
                '                               </div></div>');
        }
        else{
            $('.frequency-fields').remove();
            $('.pay-frequency-fields').remove();
        }
    });
    //Change selected weeks on changing week day for every other week type.
    $(document).on('change', '.frequency-week-day', function () {
        var selected_week_day = $("option:selected", this).val();
        var Week = [[], [], [], []];
        Week = accrualWeek(selected_week_day);
        console.log(Week);
        $('#Weekday').empty();
        $('#Weekday').append('<span>Which ' + selected_week_day + 's?</span>');
        $('#Weekdays').empty();
        $('#Weekdays').append(' <option value=' + Week[1]['firstWeek'] + '>' + Week[1]['firstWeek'] + '</option>\n' +
            '                                        <option value=' + Week[1]['secondWeek'] + '>' + Week[1]['secondWeek'] +'</option>\n');
    });
});

function payDates ()
{
    $(document).ready(function () {
        if($('#frequency').val() == 'Weekly'){
            if ($('#weekly_week_day').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    week_day: $('#weekly_week_day').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
        if($('#frequency').val() == 'Every other week'){
            if ($('#every_week_day').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    week_day: $('#every_week_day').val(),
                    dates: $('#Weekdays').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
        if($('#frequency').val() == 'Twice a monthly'){
            if ($('#twice_month_first_date').val() != '' && $('#twice_month_second_date').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    first_day: $('#twice_month_first_date').val(),
                    second_day: $('#twice_month_second_date').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
        if($('#frequency').val() == 'Monthly'){
            if ($('#monthly_date').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    date: $('#monthly_date').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
        if($('#frequency').val() == 'Quarterly'){
            if ($('#quarterly_first_date').val() != '' && $('#quarterly_first_month').val() != '' && $('#quarterly_second_date').val() != '' && $('#quarterly_second_month').val() != '' && $('#quarterly_third_date').val() != '' && $('#quarterly_third_month').val() != '' && $('#quarterly_fourth_date').val() != '' && $('#quarterly_fourth_month').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    first_day: $('#quarterly_first_date').val(),
                    first_month: $('#quarterly_first_month').val(),
                    second_day: $('#quarterly_second_date').val(),
                    second_month: $('#quarterly_second_month').val(),
                    third_day: $('#quarterly_third_date').val(),
                    third_month: $('#quarterly_third_month').val(),
                    fourth_day: $('#quarterly_fourth_date').val(),
                    fourth_month: $('#quarterly_fourth_month').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
        if($('#frequency').val() == 'Twice a yearly'){
            if ($('#twice_year_first_date').val() != '' && $('#twice_year_first_month').val() != '' && $('#twice_year_second_date').val() != '' && $('#twice_year_second_month').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    first_day: $('#twice_year_first_date').val(),
                    first_month: $('#twice_year_first_month').val(),
                    second_day: $('#twice_year_second_date').val(),
                    second_month: $('#twice_year_second_month').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
        if($('#frequency').val() == 'Yearly'){
            if ($('#yearly_date').val() != '' && $('#yearly_month').val() != '' && $('#pay_days').val() != '' && $("#exceptional_pay_day").val() != '') {
                var data = {
                    frequency: $('#frequency').val(),
                    date: $('#yearly_date').val(),
                    month: $('#yearly_month').val(),
                    pay_days: $('#pay_days').val(),
                    exceptional_pay_day: $("#exceptional_pay_day").val()
                };

                $.ajax({
                    url: '/en/pay-schedule/getPayDates',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data) {
                            var i = 0;
                            var dataSet = [];
                            $.each(data, function(index, date) {
                                if (date.adjustment == true) {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'"><div class="d-flex"><div class="col-9 p-0 m-0">'+date.pay_date+' </div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted for weekend or holiday" data-feather="info"></i></div><input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                } else {
                                    dataSet[i++] = ['<input type="hidden" name="period_start_dates[]" value="'+date.period_start+'">'+date.period_start, '<input type="hidden" name="period_end_dates[]" value="'+date.period_end+'">'+date.period_end, '<input type="hidden" name="pay_dates[]" value="'+date.pay_date+'">'+date.pay_date+'<input type="hidden" name="adjustments[]" value="'+date.adjustment+'">'];
                                }
                            });
                            $('#dates-preview-table').DataTable().destroy();
                            $('#dates-preview-table').DataTable({
                                "lengthMenu": [ [-1], ["All"] ],
                                "ordering": false,
                                data: dataSet,
                                "drawCallback": function( settings ) {
                                    feather.replace();
                                }
                            });
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    }
                });
            }
        }
    });
}