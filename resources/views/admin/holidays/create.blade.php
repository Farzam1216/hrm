@extends('layouts/contentLayoutMaster')
@section('title','Add Holiday')
@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form" id="holidays-form" action="@if(isset($locale)) {{route('holidays.store', [$locale])}} @else {{route('holidays.store', ['en'])}} @endif" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="assigned_to_filter" id="assigned_to_filter">
                    <div class="row">
                        <div class="col-md-6 col-12 form-group">
                            <label class="control-label" for="name">{{__('language.Holiday')}} {{__('language.Name')}} <span class="text-danger">*</span></label>
                            <input type="text" name="holiday_name" id="holiday_name" data-msg-required="Holiday name is required." placeholder="{{__('language.Enter')}} {{__('language.Holiday')}} {{__('language.Name')}}" class="form-control">
                        </div>
                    </div>

                    <div class="row" id="single_date_div">
                        <div class="col-12">
                            <label for="date">{{__('language.Date')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-6 form-group">
                            <input type="text" id="single_date_input" name="single_date" class="form-control flatpickr-basic" data-msg-required="Holiday date is required." placeholder="YYYY-MM-DD" />
                        </div>
                        <div class="col-6 pt-1 pl-0">
                            <a class="text-primary" id="multiple_dates"><u>More than one day?</u></a>
                        </div>
                    </div>

                    <div class="row" id="multiple_dates_div" style="display: none;">
                        <div class="col-12">
                            <label for="date_range">{{__('language.Date Range')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-6 form-group">
                            <input type="text" id="multiple_dates_input" name="multiple_dates" class="form-control flatpickr-range" data-msg-required="Holiday date is required." placeholder="YYYY-MM-DD" />
                        </div>
                        <div class="col-6 pt-1 pl-0">
                            <a class="text-primary" id="single_date"><u>Single day?</u></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-1">
                            <label class="control-label h6" for="pay_rate" data-toggle="tooltip" data-original-title="Pay rate will be calculated as 0x, 0.5x, 1x etc">Employees receive the following pay rate for any hours worked on this holiday</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="control-label" for="pay_rate" data-toggle="tooltip" data-original-title="Pay rate will be calculated as 0x, 0.5x, 1x etc">Only applies to hourly employees</label>
                        </div>
                        <div class="col-6 form-group">
                            <input type="text" name="pay_rate" id="pay_rate" placeholder="Enter Pay Rate i.e. 0, 0.5, 1 etc" class="form-control" data-toggle="tooltip" data-original-title="Pay rate will be calculated as 0x, 0.5x, 1x etc">
                        </div>
                        <div class="col-6 pt-1 pl-0">
                            <a id="single_date">x the normal base rate</a>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <h6 class="col-12">This Holiday is for?</h6>
                    </div>

                    <div class="row col-12 mt-1">
                        <i style="width: 17px; height: 17px;" data-feather='users'></i>
                        <label class="h5" id="all_employees_label">&nbsp; <b class="employees-label">All Employees</b></label>
                        <label class="h5 hidden" id="selected_employees_label">&nbsp; <b class="employees-label">Just These Employees</b></label>
                        <a href="#" class="ml-1" data-toggle="modal" data-target="#edit-selection"><u>Edit</u></a>

                        <div class="modal modal-slide-in fade" id="edit-selection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content pt-0">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel1">Filter Options</h4>
                                    </div> 
                                    <div class="mr-2 ml-2 mt-1 mb-1">
                                        <div class="form-group">
                                            <label for="basicSelect">Assign To</label>
                                            <select class="form-control" id="assign_to" name="assign_to">
                                                <option value="all employees">All Employees</option>
                                                <option value="only some employees">Only Some Employees</option>
                                            </select>
                                        </div>
                                        @php $filters = 'not available'; @endphp
                                        @if(count($departments) > 0 || count($divisions) > 0 || count($employment_statuses) > 0 || count($designations) > 0 || count($locations) > 0)
                                            @php $filters = 'available'; @endphp
                                            <div class="hidden" id="employees_filter">
                                                <div class="row col-12 mt-1 h6">
                                                    Filter Employees By
                                                </div>
                                                <div class="card bg-light">
                                                    <div style="max-height: 380px; overflow: auto;">
                                                        @if(count($departments) > 0)
                                                            <div class="card-body bg-white rounded m-1 border">
                                                                <a class="row justify-content-between pl-1 pr-1" onclick="dropdown('departments');" id="departments">
                                                                    <div>
                                                                        Department <i id="departments_icon_right" data-feather="chevron-right"></i><i class="hidden" id="departments_icon_down" data-feather="chevron-down"></i>
                                                                    </div>
                                                                    <span class="badge badge-light-primary badge-pill float-right">
                                                                        {{count($departments)}}
                                                                    </span>
                                                                </a>
                                                                <div class="row bg-white pt-1 pl-1 pr-1 hidden" id="departments_checkboxes">
                                                                    @foreach($departments as $key => $department)
                                                                        <div class="col-12 custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="employees[department][]" value="{{$department->id}}" id="departments{{$key}}" selected_value="{{$department->department_name}}"/>
                                                                            <label class="custom-control-label" for="departments{{$key}}"  style="display: inline-block;">{{$department->department_name}}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if(count($divisions) > 0)
                                                            <div class="card-body bg-white rounded m-1 border">
                                                                <a class="row justify-content-between pl-1 pr-1" onclick="dropdown('divisions');" id="divisions">
                                                                    <div>
                                                                        Division <i id="divisions_icon_right" data-feather="chevron-right"></i><i class="hidden" id="divisions_icon_down" data-feather="chevron-down"></i>
                                                                    </div>
                                                                    <span class="badge badge-light-primary badge-pill float-right">
                                                                        {{count($divisions)}}
                                                                    </span>
                                                                </a>
                                                                <div class="row bg-white pt-1 pl-1 pr-1 hidden" id="divisions_checkboxes">
                                                                    @foreach($divisions as $key => $division)
                                                                        <div class="col-12 custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="employees[division][]" value="{{$division->id}}" id="divisions{{$key}}" selected_value="{{$division->name}}"/>
                                                                            <label class="custom-control-label" for="divisions{{$key}}">{{$division->name}}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if(count($employment_statuses) > 0)
                                                            <div class="card-body bg-white rounded m-1 border">
                                                                <a class="row justify-content-between pl-1 pr-1" onclick="dropdown('employment_statuses');" id="employment_statuses">
                                                                    <div>
                                                                        Employment Status <i id="employment_statuses_icon_right" data-feather="chevron-right"></i><i class="hidden" id="employment_statuses_icon_down" data-feather="chevron-down"></i>
                                                                    </div>
                                                                    <span class="badge badge-light-primary badge-pill float-right">
                                                                        {{count($employment_statuses)}}
                                                                    </span>
                                                                </a>
                                                                <div class="row bg-white pt-1 pl-1 pr-1 hidden" id="employment_statuses_checkboxes">
                                                                    @foreach($employment_statuses as $key => $employment_status)
                                                                        <div class="col-12 custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="employees[employment_status][]" value="{{$employment_status->id}}" id="employment_statuses{{$key}}" selected_value="{{$employment_status->employment_status}}"/>
                                                                            <label class="custom-control-label" for="employment_statuses{{$key}}">{{$employment_status->employment_status}}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if(count($designations) > 0)
                                                            <div class="card-body bg-white rounded m-1 border">
                                                                <a class="row justify-content-between pl-1 pr-1" onclick="dropdown('designations');" id="designations">
                                                                    <div>
                                                                        Job Title <i id="designations_icon_right" data-feather="chevron-right"></i><i class="hidden" id="designations_icon_down" data-feather="chevron-down"></i>
                                                                    </div>
                                                                    <span class="badge badge-light-primary badge-pill float-right">
                                                                        {{count($designations)}}
                                                                    </span>
                                                                </a>
                                                                <div class="row bg-white pt-1 pl-1 pr-1 hidden" id="designations_checkboxes">
                                                                    @foreach($designations as $key => $designation)
                                                                        <div class="col-12 custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="employees[designation][]" value="{{$designation->id}}" id="designations{{$key}}" selected_value="{{$designation->designation_name}}"/>
                                                                            <label class="custom-control-label" for="designations{{$key}}">{{$designation->designation_name}}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if(count($locations) > 0)
                                                            <div class="card-body bg-white rounded m-1 border">
                                                                <a class="row justify-content-between pl-1 pr-1" onclick="dropdown('locations');" id="locations">
                                                                    <div>
                                                                        Location <i id="locations_icon_right" data-feather="chevron-right"></i><i class="hidden" id="locations_icon_down" data-feather="chevron-down"></i>
                                                                    </div>
                                                                    <span class="badge badge-light-primary badge-pill float-right">
                                                                        {{count($locations)}}
                                                                    </span>
                                                                </a>
                                                                <div class="row bg-white pt-1 pl-1 pr-1 hidden" id="locations_checkboxes">
                                                                    @foreach($locations as $key => $location)
                                                                        <div class="col-12 custom-control custom-checkbox">
                                                                            <input type="checkbox" class="custom-control-input" name="employees[location][]" value="{{$location->id}}" id="locations{{$key}}" selected_value="{{$location->name}}"/>
                                                                            <label class="custom-control-label" for="locations{{$key}}">{{$location->name}}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div id="checkbox-error" class="error hidden">No checkbox selected.</div>
                                            </div>
                                        @endif
                                        <div id="filters-error" class="error hidden">Filters not available.</div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-warning" data-dismiss="modal" aria-label="Close">{{__('language.Cancel')}}</button>
                                        <button type="button" id="save" class="btn btn-primary waves-effect waves-float waves-light" data-dismiss="modal">{{__('language.Save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-12 pb-1" id="selected_filters"></div>

                    <hr>

                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary waves-effect waves-float waves-light mr-1">{{__('language.Add')}}</button>
                            <button type="reset" onclick="window.location.href='@if(isset($locale)){{route('holidays.index', [$locale])}} @else {{route('holidays.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect">
                                {{__('language.Cancel')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        document.getElementById("selected_filters").innerHTML = "";
        $("#assigned_to_filter").val('all employees');

        if ($("#assign_to").val() == "only some employees") {
            $("#all_employees_label").addClass('hidden');
            $("#selected_employees_label").removeClass('hidden');

            $('#employees_filter input:checked').each(function() {
                $("#selected_filters").append("<div class='col-md-4 pt-1'>"+$(this).attr('selected_value')+"&nbsp;&nbsp;<i class='text-success'  data-feather='check-circle'></i></div>");
            });

            $("#selected_filters").removeClass("hidden");
        } else {
            $("#selected_employees_label").addClass('hidden');
            $("#all_employees_label").removeClass('hidden');
            
            $('#employees_filter input:checked').each(function() {
                $(this).prop('checked', false);
            });

            $("#selected_filters").addClass("hidden");
        }
        feather.replace();
    });

    $("#multiple_dates").on('click', function(){
        $("#single_date_input").val('');
        document.getElementById('single_date_div').style.display = "none";
        document.getElementById('multiple_dates_div').style.display = "";
    });

    $("#single_date").on('click', function(){
        $("#multiple_dates_input").val('');
        document.getElementById('multiple_dates_div').style.display = "none";
        document.getElementById('single_date_div').style.display = "";
    });

    $("#assign_to").on('change', function(){
        var filters = '{!! $filters !!}';

        if (this.value == "only some employees") {
            if (filters == 'available') {
                $("#employees_filter").removeClass("hidden");
            }
        } else {
            $("#employees_filter").addClass("hidden");
        }
    });

    function dropdown(name) {
        $class = $("#"+name).hasClass("open");
        if ($class == false) {
            $("#"+name).addClass("open");
            $("#"+name+"_icon_right").addClass("hidden");
            $("#"+name+"_icon_down").removeClass("hidden");
            $("#"+name+"_checkboxes").removeClass("hidden")
        } else {
            $("#"+name).removeClass("open");
            $("#"+name+"_icon_right").removeClass("hidden");
            $("#"+name+"_icon_down").addClass("hidden");
            $("#"+name+"_checkboxes").addClass("hidden")
        }
    }

    $("#save").on('click', function(){
        document.getElementById("selected_filters").innerHTML = "";
        $("#assigned_to_filter").val('');

        if ($("#assign_to").val() == "only some employees") {
            var filters = '{!! $filters !!}';
            
            if (filters == 'available') {
                if ($('#employees_filter input:checked').length > 0) {
                    $("#all_employees_label").addClass('hidden');
                    $("#selected_employees_label").removeClass('hidden');
                    this.setAttribute('data-dismiss', 'modal');

                    $('#employees_filter input:checked').each(function() {
                        $("#selected_filters").append("<div class='col-md-4 pt-1'>"+$(this).attr('selected_value')+"&nbsp;&nbsp;<i class='text-success'  data-feather='check-circle'></i></div>");
                        if ($("#assigned_to_filter").val() == '') {
                            $("#assigned_to_filter").val($(this).attr('selected_value'));
                        } else {
                            $("#assigned_to_filter").val($("#assigned_to_filter").val()+','+$(this).attr('selected_value'));
                        }
                    });

                    $("#selected_filters").removeClass("hidden");
                    $("#checkbox-error").addClass("hidden");
                } else {
                    this.setAttribute('data-dismiss', '');
                    $("#checkbox-error").removeClass("hidden");
                }
            }

            if (filters == 'not available') {
                this.setAttribute('data-dismiss', '');
               $('#assign_to').val('all employees');
               $("#filters-error").removeClass("hidden");
            }
        } else {
            $("#selected_employees_label").addClass('hidden');
            $("#all_employees_label").removeClass('hidden');
            
            $('#employees_filter input:checked').each(function() {
                $(this).prop('checked', false);
            });

            $("#selected_filters").addClass("hidden");
            $("#assigned_to_filter").val('all employees');
            $("#filters-error").addClass("hidden");
            this.setAttribute('data-dismiss', 'modal');
        }
        feather.replace();
    });
</script>
@stop
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/validations/form-holidays.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection