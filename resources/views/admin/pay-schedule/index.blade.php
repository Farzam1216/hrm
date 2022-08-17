@extends('layouts.contentLayoutMaster')
@section('title','Pay Schedules')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            @if(Auth::user()->isAdmin() || isset($permissions['paySchedule']['all']) && in_array('manage pay schedule', $permissions['paySchedule']['all']))
                <div class="card-header border-bottom pt-1 pb-1">
                    <div class="head-label">
                        <h6 class="mb-0"></h6>
                    </div>
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons flex-wrap d-inline-flex">
                            <button type="button" class="btn create-new btn-primary mr-1"
                            onclick="window.location.href='@if(isset($locale)){{route('pay-schedule.create', [$locale])}} @else {{route('pay-schedule.create', ['en'])}} @endif'" data-toggle="tooltip" data-placement="top" data-original-title="Add Pay Schedule"><i data-feather="plus"></i><span class="d-none d-lg-inline d-md-inline d-sm-none"> {{__('language.Add')}} {{__('language.Pay Schedule')}}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table id="kt_table_1" class="dt-simple-header table">
                    <thead>
                        <tr>
                            <th> {{__('language.Name')}}</th>
                            <th> {{__('language.Frequency')}}</th>
                            @if(Auth::user()->isAdmin() || isset($permissions['paySchedule']['all']) && in_array('manage pay schedule', $permissions['paySchedule']['all']))
                                <th> {{__('language.Actions')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paySchedules as $key => $paySchedule)
                            <tr>
                                <td>{{$paySchedule->name}}</td>
                                <td>{{$paySchedule->frequency}}</td>
                                @if(Auth::user()->isAdmin() || isset($permissions['paySchedule']['all']) && in_array('manage pay schedule', $permissions['paySchedule']['all']))
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#view-schedule-dates{{ $paySchedule->id }}"><i data-toggle="tooltip" data-original-title="View Schedule Dates" data-feather='calendar'></i></a>

                                        <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" data-placement="top" title="" data-toggle="tooltip" href="@if(isset($locale)){{route('pay-schedule.assign', [$locale, $paySchedule->id])}} @else {{route('pay-schedule.assign', ['en', $paySchedule->id])}} @endif" data-original-title="Assign Pay Schedule To Employees">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"class="feather feather-link-2">
                                                <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                        </a>

                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{route('pay-schedule.edit', [$locale, $paySchedule->id])}} @else {{route('pay-schedule.edit', ['en', $paySchedule->id])}} @endif" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i data-feather="edit-2"></i></a>

                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $paySchedule->id }}"><i data-feather="trash-2"></i> </a>
                                    </td>
                                @endif
                            </tr>
                            
                            <div class="modal fade text-left" id="confirm-delete{{ $paySchedule->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="@if(isset($locale)){{route('pay-schedule.destroy', [$locale, $paySchedule->id])}} @else {{route('pay-schedule.destroy', [$locale, $paySchedule->id])}} @endif" method="post">
                                            {{ csrf_field() }}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <div class="modal-header">
                                                <h4>Delete Pay Schedule</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete "{{$paySchedule->name}}" Pay Schedule?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect waves-float waves-light" data-dismiss="modal"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                                                <button type="submit" class="btn btn-danger btn-ok"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='trash-2'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Delete')}}</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                @foreach($paySchedules as $paySchedule)
                    <div class="modal modal-slide-in fade" id="view-schedule-dates{{ $paySchedule->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document" style="width:700px;">
                            <div class="modal-content pt-0">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel1">{{ucwords($paySchedule->name)}} Scheduled Dates</h4>
                                </div>

                                <div class="ml-2 mr-2 alert alert-success alert-dismissible fade hidden mt-1" id="success-message{{$paySchedule->id}}">
                                    <div class="alert-body">
                                        <strong>Success!</strong> Pay date is updated successfully
                                    </div>
                                    <button type="button" class="close" onclick='$("#success-message"+{!! $paySchedule->id !!}).removeClass("show"); $("#success-message"+{!! $paySchedule->id !!}).addClass("hidden");'>
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>

                                <div class="ml-2 mr-2 alert alert-danger alert-dismissible fade hidden mt-1" id="error-message{{$paySchedule->id}}">
                                    <div class="alert-body">
                                        <strong>Error!</strong> Something went wrong while updating pay date
                                    </div>
                                    <button type="button" class="close" onclick='$("#error-message"+{!! $paySchedule->id !!}).removeClass("show");'>
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>

                                <div class="modal-body mt-0" style="max-height:700px; overflow: auto;">                                
                                    <div class="card-datatable table-responsive pt-0 p-1">
                                        <table class="table table-sm dates-preview-table">
                                            <thead>
                                                <tr>
                                                    <th>Period Start</th>
                                                    <th>Period End</th>
                                                    <th>Pay Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($paySchedule['payScheduleDates'] as $date)
                                                    <tr>
                                                        <td>{{$date->period_start}}</td>
                                                        <td>{{$date->period_end}}</td>
                                                        <td>
                                                            <div class="date-div{{$date->id}}" id="date-div{{$date->id}}">
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
                                                            </div>
                                                            <div class="hidden edit-div{{$date->id}} row pr-1">
                                                                <input type="text" class="form-control flatpickr-basic flatpickr-input active dates" placeholder="DD-MM-YYYY" date="{{$date->pay_date}}" onchange="changePayDate(this, {!! $date->id !!}, {!! $paySchedule->id !!});">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="date-div{{$date->id}}">
                                                                <a onclick="showEditField({!! $date->id !!});" data-toggle="tooltip" data-original-title="Edit Pay Date"><i data-feather='edit-3'></i></a>
                                                            </div>
                                                            <div class="hidden edit-div{{$date->id}}">
                                                                <a onclick="hideEditField({!! $date->id !!});" data-toggle="tooltip" data-original-title="Cancel"><i data-feather='x'></i></a>
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
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
@section('vendor-script')
    {{-- Vendor js files --}}
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
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset('js/scripts/pay-schedule-index-script.js') }}"></script>
@endsection
