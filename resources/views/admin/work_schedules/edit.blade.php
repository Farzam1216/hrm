@extends('layouts/contentLayoutMaster')
@section('title','Edit Work Schedule')
@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
    <!-- Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Work Schedule</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" id="work-schedule"
                              action="@if(isset($locale)){{url($locale.'/work-schedule/'.$workSchedule->id)}} @else {{url('en/work-schedule'.$workSchedule->id)}} @endif"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="title">{{__('language.Title')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" required
                                               data-msg-required="Title is required."
                                               placeholder="{{__('language.Enter')}} {{__('language.Title')}}"
                                               value="{{old('title',$workSchedule->title)}}"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6 col-12"></div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="start_time">Start Time <span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="Start time is required."
                                                type="text"
                                                name="start_time"
                                                id="start_time"
                                                class="form-control"
                                                placeholder="Start Time"
                                                value="{{old('start_time',$workSchedule->schedule_start_time)}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="start_time">Flex Time For Start(after this time the employee will be considered late)<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="Flex Start Time is required."
                                                type="text"
                                                name="flex_time_in"
                                                id="flex_time_in"
                                                class="form-control"
                                                placeholder="Flex Time In"
                                                value="{{old('flex_time_in',$workSchedule->flex_time_in)}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="end_time">Break Time<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="Break time is required."
                                                type="text"
                                                name="break_time"
                                                id="break_time"
                                                class="form-control"
                                                placeholder="Break Time"
                                                value="{{old('break_time',$workSchedule->schedule_break_time)}}"
                                        />
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="end_time">End Break Time<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="End Break time is required."
                                                type="text"
                                                name="back_time"
                                                id="back_time"
                                                class="form-control"
                                                placeholder="End Break Time"
                                                value="{{old('back_time',$workSchedule->schedule_back_time)}}"
                                        />
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="end_time">End Time<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="End time is required."
                                                type="text"
                                                name="end_time"
                                                id="end_time"
                                                class="form-control"
                                                placeholder="End Time"
                                                value="{{old('end_time',$workSchedule->schedule_end_time)}}"
                                        />
                                    </div>
                                </div>
                                @php $days = explode(',',$workSchedule->non_working_days) @endphp
                                <!-- Multiple Select for non-working days selection-->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="normalMultiSelect">Non Working Days<span class="text-danger">*</span></label>
                                        <select class="select2 form-control" name="non_working_days[]" multiple>
                                            <option value="monday" @if(in_array('monday', $days)) selected @endif>Monday</option>
                                            <option value="tuesday" @if(in_array('tuesday', $days)) selected @endif>Tuesday</option>
                                            <option value="wednesday" @if(in_array('wednesday', $days)) selected @endif>Wednesday</option>
                                            <option value="thursday" @if(in_array('thursday', $days)) selected @endif>Thursday</option>
                                            <option value="friday" @if(in_array('friday', $days)) selected @endif>Friday</option>
                                            <option value="saturday" @if(in_array('saturday', $days)) selected @endif>Saturday</option>
                                            <option value="sunday" @if(in_array('sunday', $days)) selected @endif>Sunday</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12"><br>
                                    <button type="submit" class="btn btn-primary mr-1">{{__('language.Update')}}</button>
                                    <button type="reset"
                                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/work-schedule')}} @else {{url('en/work-schedule')}} @endif'"
                                            class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/validations/form-work-schedule.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script>
        var startTime = $('#start_time');
        var startTimeValue = '{!! $workSchedule->start_time !!}';
        var endTimeValue = '{!! $workSchedule->end_time !!}';
        var breakTime = $('#break_time');
        var backTime = $('#back_time');
        var FlexTimeIn = $('#flex_time_in');
        var endTime = $('#end_time');
        if (startTime.length) {
            // Basic time
            var start_time = startTime.pickatime({});

        }

        if (endTime.length) {
            // Basic time
            var end_time = endTime.pickatime({});
        }
        if (breakTime.length) {
            // Basic time
            var break_time = breakTime.pickatime({

            });
        }
        if (FlexTimeIn.length) {
            // Basic time
            var flex_time_in = FlexTimeIn.pickatime({
                clear: '',
                interval: 15,
            });
        }
        if (backTime.length) {
            // Basic time
            var back_time = backTime.pickatime({});
        }

        $("#start_time").on('change', function(){
            var flexTimeIn = $('#flex_time_in').pickatime().pickatime('picker');
            var breakTime = $('#break_time').pickatime().pickatime('picker');
            var backTime = $('#back_time').pickatime().pickatime('picker');
            var endTime = $('#end_time').pickatime().pickatime('picker');
            
            startTimeExplode = $(this).val().split(':');
            startTimeExplodeMinutes = startTimeExplode[1].split(' ');
            time = startTimeExplode[0]+':30'+' '+startTimeExplodeMinutes[1];
            
            endTime.set('select', time, { format: 'h:i A' });
            flexTimeIn.set('select', $(this).val(), { format: 'h:i A' })
            breakTime.set('select', $(this).val(), { format: 'h:i A' });
            backTime.set('select', $(this).val(), { format: 'h:i A' });
        });
    </script>
@endsection