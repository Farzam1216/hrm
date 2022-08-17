@extends('layouts/contentLayoutMaster')
@section('title','Add Work Schedule')
@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
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
                        <h4 class="card-title">Work Schedule </h4>
                    </div>
                    <div class="card-body">
                        <form class="form" id="work-schedule"
                              action="@if(isset($locale)){{url($locale.'/work-schedule')}} @else {{url('en/work-schedule')}} @endif"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="title">{{__('language.Title')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" 
                                               data-msg-required="Title is required."
                                               placeholder="{{__('language.Enter')}} {{__('language.Title')}}"
                                               class="form-control" value="{{old('title')}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12"></div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="start_time">Start Schedule Time <span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="Start time is required."
                                                type="text"
                                                name="start_time"
                                                id="start_time"
                                                class="form-control"
                                                placeholder="Start Time"
                                                value="{{old('start_time')}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="start_time">Flex Start Time(after this time the employee will be considered late)<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="Flex Start Time is required."
                                                type="text"
                                                name="flex_time_in"
                                                id="flex_time_in"
                                                class="form-control"
                                                placeholder="Flex Time In"
                                                value="{{old('flex_time_in')}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="break_time">Break Time<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="Break time is required."
                                                type="text"
                                                name="break_time"
                                                id="break_time"
                                                class="form-control"
                                                placeholder="Break Time"
                                                value="{{old('break_time')}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="back_time">End Break Time<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="End Break time is required."
                                                type="text"
                                                name="back_time"
                                                id="back_time"
                                                class="form-control"
                                                placeholder="End Break Time"
                                                value="{{old('back_time')}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="end_time">End Schedule Time<span
                                                    class="text-danger">*</span></label>
                                        <input
                                                required
                                                data-msg-required="End time is required."
                                                type="text"
                                                name="end_time"
                                                id="end_time"
                                                class="form-control"
                                                placeholder="End Time"
                                                value="{{old('end_time')}}"
                                        />
                                    </div>
                                </div>

                                <!-- Multiple Select for non-working days selection-->
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="normalMultiSelect">Non Working Days<span class="text-danger">*</span></label>
                                        <select class="select2 form-control" name="non_working_days[]" multiple>
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                            <option value="sunday">Sunday</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12"><br>
                                    <button type="submit" class="btn btn-primary mr-1">{{__('language.Add')}}</button>
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
        var endTime = $('#end_time');
        var breakTime = $('#break_time');
        var backTime = $('#back_time');
        var FlexTimeIn = $('#flex_time_in');
        var FlextimeBreak = $('#flex_time_break');
        if (startTime.length) {
            // Basic time
            var start_time = startTime.pickatime({
                clear: '',
                interval: 60,
            });
        }
        if (endTime.length) {
            // Basic time
            var end_time = endTime.pickatime({
                clear: '',
                interval: 30,
            });
        }
        if (breakTime.length) {
            // Basic time
            var break_time = breakTime.pickatime({
                clear: '',
                interval: 15,
            });
        }
        if (backTime.length) {
            // Basic time
            var back_time = backTime.pickatime({
                clear: '',
                interval: 15,
            });
        }
        if (FlexTimeIn.length) {
            // Basic time
            var flex_time_in = FlexTimeIn.pickatime({
                clear: '',
                interval: 15,
            });
        }
        if (FlextimeBreak.length) {
            // Basic time
            var flex_time_break = FlextimeBreak.pickatime({
                clear: '',
                interval: 15,
            });
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