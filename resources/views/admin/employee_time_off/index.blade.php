@extends('layouts.contentLayoutMaster')
@section('title','Time Off Requests')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/swiper.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-swiper.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div>
                    <p class="mb-n1" style="text-align:right">Accrual Level Start Date: <span class="text-primary">date</span></p>
                    <hr>
                </div>
                @php $check = 'no'; @endphp
                @foreach ($time_off_types as $type)
                    @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id]) && $permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id] != "request" || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                        @php $check = 'yes'; @endphp
                    @endif
                @endforeach
                <!-- centered-slides swiper for policies -->
                @if($check == 'yes')
                <section id="component-swiper-multiple">
                    <div class="card">
                        <div class="card-body">
                            <div class="swiper-multiple swiper-container">
                                <div class="swiper-wrapper">
                                    @foreach ($time_off_types as $type)
                                        @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id]) && $permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id] != "request")
                                            <div class="swiper-slide rounded bg-light time-off-type-box" onmouseenter="document.getElementById('buttons'+{!! $type->id !!}).style.display = '';" onmouseleave="document.getElementById('buttons'+{!! $type->id !!}).style.display = 'none';" style="height: 210px;">
                                                <div class="row justify-content-center time-off-btns" style="display:none;" id="buttons{{$type->id}}">
                                                    @if(Auth::user()->isAdmin() || isset($permissions['timeofftype'][$employee->id]['request timeofftype '.$type->type_id]) || isset($permissions['timeofftype'][$employee->id]['request timeofftype all']) || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all'] ) || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                                                        <button type="button" onclick="getBalance({{$type->id}})" class="btn btn-sm" data-toggle="modal" data-target="#time-off" title="Add Time-Off">
                                                            <i class="fas fa-calendar-plus fa-2x fa-color-pto" id="type-value" value="{{$type->timeOffType->id}}"></i>
                                                        </button>
                                                    @endif
                                                    @if ($type->accrual_option != 'None')
                                                        <button type="button" class="btn btn-sm" id="calculate{{$type->timeOffType->id}}" onclick="calculateTimeOff({{$type}},{{$type->type_id}},{{$type->attached_policy_id}})" data-toggle="modal" data-target="#calculate-time-off{{$type->id}}" title="Calculate Time-Off">
                                                            <i class="fas fa-calculator fa-2x fa-color-pto"></i>
                                                        </button>
                                                    @endif
                                                    @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all'] ))
                                                        @if ($type->accrual_option != 'None')
                                                            <button type="button" class="btn btn-sm" id="" onclick="getcurrentBalance({{$type}})" data-toggle="modal" data-target="#adjust-balance" title="Adjust Balance">
                                                                <i class="fas fa-cash-register fa-2x fa-color-pto"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="swiper-text text-center pt-2" id="text{{$type->id}}">
                                                    {{$type->timeOffType->time_off_type_name}}
                                                    @php
                                                        $balance = $type->timeofftransaction->sum('hours_accrued'); //getcy
                                                        $ceil = ceil($balance);
                                                    @endphp
                                                    <p>
                                                        @if($type->accrual_option == "None")
                                                            <h4 class="font-weight-bolder" id="available-hours{{$type->id}}">
                                                                {{abs($usedbalance[$type->id]->sum('hours_accrued'))}}
                                                            </h4> Hours Used
                                                        @else
                                                            <h4 class="font-weight-bolder @if($ceil < 0) text-danger @endif" id="available-hours{{$type->id}}">
                                                                {{$ceil}}
                                                            </h4> Hours Available
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="text-center">
                                                    @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all'] ))
                                                        <a type="button" class="small-box-footer pl-1" data-toggle="modal"
                                                           id="accrualType{{$type->timeOffType->id}}"
                                                          onclick="changeAccrualOption({{$type->id}},{{$type->type_id}},{{$type->attached_policy_id}})"
                                                           data-target="#accrual-options">
                                                            More info <i class="fas fa-arrow-circle-right"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </section>
                @else
                    <div class="row">
                        <div class="col-sm-12">
                            <br>
                            <h6 class="text-center"><b>No Time Types Assigned. You cannot make Time Off Requests.</b></h6>
                            <br>
                            <hr>
                        </div>

                    </div>
                @endif
                <!--/ centered-slides swiper for policies -->
                
                <!----Accrual Options Modal ----->
                <div class="modal modal-slide-in fade" id="accrual-options" tabindex="-1" role="dialog" width="1000">
                    <div class="modal-dialog" role="document" style="width:700px;">
                        <div class="modal-content pt-0">
                            <form action="@if(isset($locale)) {{route('timeoff.save-accrual-option', [$locale, $employee->id])}} @else {{route('timeoff.save-accrual-option', ['en', $employee->id])}} @endif" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="employee_id" value="{{$employee->id}}">
                                <input id="assigntimeoftypeid" type="hidden" name="timeOfTypeId">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="myModalLabel1">Accrual Options</h4>
                                </div>
                                <div class="modal-body pt-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="accrual-options">Accrual Policy</label>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <select id="accrualoptions" name="policyId" class="form-control"></select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group input-group" id="accrualOptionPreviewBtnRow">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input class="form-control flatpickr-basic flatpickr-input" type="date" name="policyStartDate" id="policyStartDate" required="" placeholder="YYYY-MM-DD">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="btn-preview">Preview</button>

                                    <br><br>

                                    <div class="card-datatable" id="accrualOptionPreviewTableRow">
                                        <table class="preview_table table dataTable table-sm">
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!----Accrual Options Modal End----->

                <!----Fixed: Adjust Balance Modal ----->
                <div class="modal modal-slide-in fade" id="adjust-balance" tabindex="-1" role="dialog" aria-labelledby="adjust-balance-label" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width:500px;">
                        <div class="modal-content pt-0">
                            <form id="adjust-balance-form" action="@if(isset($locale)) {{route('timeoff.adjust-balance', [$locale, $employee->id])}} @else {{route('timeoff.adjust-balance', [$locale, $employee->id])}} @endif" method="post">
                                {{ csrf_field() }}
                                <input id="pol_id" type="hidden" name="policy">
                                <input id="type_id" type="hidden" name="type">
                                <div class="modal-header">
                                    <h4 class="modal-title">Adjust Balance</h4>
                                </div>

                                <div class="modal-body pt-1">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label" for="accrual-options">Amount</label>
                                            <select class="form-control" name="amount-options" id="amount-options">
                                                <option>Add</option>
                                                <option>Subtract</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Hours</label>
                                            <input name="adjust-hours" id="adjust-hours" type="text" class="form-control" value="">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="control-label">Effective Date</label>
                                            <div class="form-group input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control flatpickr-basic flatpickr-input" placeholder="YYYY-MM-DD" name="effective-date" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label">Message</label>
                                            <textarea class="form-control" id="adjust-balalnce-note" name="note" id="" cols="" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row ">
                                            <div class="col-md-12">
                                                <label class="control-label">Summary</label>
                                                <hr class="mt-0">
                                            </div>
                                            <div class="col-md-12 container row justify-content-between">
                                                <div class="col-md-6">Current Vacation Balance</div>
                                                <div class="row justify-content-end">
                                                    <input class="col-md-4 border-0 bg-white text-right pr-0" type="text" id="current-balance" name="current-balance" value="" readonly disabled>
                                                    <div>&nbsp;hours</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 container row justify-content-between">
                                                <div class="col-md-6" id="addorsubtract-text"></div>
                                                <div class="row justify-content-end">
                                                    <input class="col-md-4 border-0 bg-white text-right pr-0" type="text" id="addorsubtract-balance" value="" readonly disabled>
                                                    <div>&nbsp;hours</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                            </div>
                                            <div class="col-md-12 container row justify-content-between">
                                                <div class="col-md-6">New Vacation Balance</div>
                                                <div class="row justify-content-end">
                                                    <input class="col-md-4 border-0 bg-white text-right pr-0" type="text" id="new-balance" name="new-balance" value="" readonly disabled>
                                                    <div>&nbsp;hours</div>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group col-md-12">
                                        <!-------- Summary Heading------>
                                        
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!----Adjust Balance Modal End----->

                <!----Calculate Time Off Modal----->
                <div class="modal modal-slide-in fade" id="calculate-time-off" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:700px;">
                        <div class="modal-content pt-0">
                            <div class="modal-header">
                                {{__('language.Calculate Time Off')}}
                            </div>
                            <div class="modal-body pt-1 mt-0">
                                <div class="row">
                                    <!--------As of Date------>
                                    <div class="col-md-12">
                                        <label class="control-label">{{__('language.As of Date')}}</label>
                                        <div class="form-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input class="form-control flatpickr-basic flatpickr-input" placeholder="" type="date" id="date" name="date">
                                        </div>
                                    </div>
                                    <!--------/.As of Date------>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered calculate_time_off_table">
                                        </table>
                                    </div>
                                    <!--------/.Time Off Calculation------>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-----/.Calculate Time Off Modal End---->

                <!-- /.row -->
                <!--Carousel Add Time off-->
                <div class="modal modal-slide-in fade" id="time-off" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content pt-0">
                            <form id="requestTimeOffForm" action=" @if(isset($locale)){{route('timeoff.store', [$locale, $employee->id])}} @else {{route('timeoff.store', ['en', $employee->id])}} @endif" method="post">
                                @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                                    @if($employee->id != Auth::id())
                                        <input type="hidden" name="permissionCheck" value="true">
                                    @else
                                        <input type="hidden" name="permissionCheck" value="false">
                                    @endif
                                @else
                                    <input type="hidden" name="permissionCheck" value="false">
                                @endif

                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <h4>{{__('language.Request Time Off')}}</h4>
                                </div>
                                <div class="modal-body pt-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Date range:</label><span class="text-danger">*</span>
                                                <!-- Date range -->
                                                <div class="input-group date-range" gethours="{{$getHours}}">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control float-right" id="reservation" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                                                </div>
                                                <!-- /.Date range -->
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Time Off Type</label>
                                                <div>
                                                    <select class="form-control" id="typeoff_type_dropdown" name="assign_timeoff_type_id">
                                                        @foreach ($time_off_types as $type)
                                                            @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['request time off decision']) || isset($permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id]) && $permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id] != "request") 
                                                                <option value="{{$type->id}}">
                                                                    {{ $type->timeOffType->time_off_type_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="tot">
                                    </div>

                                    <div class="row">
                                        <!-------- Message Heading------>
                                        <div class="col-md-12">
                                            <label class="control-label"><b>Message:</b></label>
                                        </div>
                                        <div class="col-md-12">
                                        <textarea class="form-control " id="timeoff-note" name="note" id="" cols=""
                                                  rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                    <button type="submit" onclick="validateDateRange();" class="btn btn-primary waves-effect waves-float waves-light btn-ok">{{__('language.Send')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!---------/.Time-off Types Carousal-------->
                <br>
                <div class="row ">
                    <div class="col-sm-12">
                        <div class="d-inline-flex text-success"><i class="fas fa-calendar-alt"></i></div>
                        <h5 class="text-success d-inline-flex"><b> Upcoming Time Off</b></h5>
                        <hr>
                    </div>
                </div>
                @if(count($upcomingRequests) > 0)
                    @foreach ($upcomingRequests as $upcomingRequest)
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{\Carbon\Carbon::parse($upcomingRequest->to)->format('d-m-Y')}} to {{\Carbon\Carbon::parse($upcomingRequest->from)->format('d-m-Y')}}
                                        <div class="">
                                <span id="sum{{$upcomingRequest->id}}">
                                    {{ $requestHours[$upcomingRequest->id] = $upcomingRequest->requestTimeOffDetail->sum('hours')}}
                                </span>
                                            hours of
                                            <span class="">
                                    {{$upcomingRequest->assignTimeOff->timeOffType->time_off_type_name}}
                                </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4" edit="{{$upcomingRequest->note}}">
                                        {{$upcomingRequest->note}}
                                    </div>
                                    <div class="col-md-4 text-right">
                                        @if ($upcomingRequest->status != 'approved')
                                            @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                                                <a class="btn btn-default btnApprove" data-toggle="modal" title="Approve"
                                                   data-target="#approve{{ $upcomingRequest->id }}" data-original-title="approve">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            @endif
                                        @endif

                                        {{--FIXME: Approve Modal for Upcoming Time oFF --}}
                                        <div class="modal fade" id="approve{{ $upcomingRequest->id }}" tabindex="-1"
                                             role="dialog" aria-labelledby="Approve Request" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="@if(isset($locale)){{route('timeoff.approve-request', [$locale, $employee->id, $upcomingRequest->id])}} @else {{route('timeoff.approve-request', ['en', $employee->id, $upcomingRequest->id])}} @endif" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <h5>Approve Time Off Request</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <h5>{{__('language.Are you sure you want to Approve this Time Off Type?')}}
                                                            </h5>
                                                            <div class="form-group">
                                                                <label>Add Comment (Optional):</label>
                                                                <textarea class="form-control " id="add-comment" name="comment" cols="" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-warning waves-effect"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-float waves-light">{{__('language.Approve')}}</button>
                                                            <input id="requesttimeoffid" type="hidden" name="requesttimeoffid"
                                                                   value="{{$upcomingRequest->id}}">
                                                            <input id="commentedBy" type="hidden" name="commentedById"
                                                                   value="{{auth()->user()->id}}">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Ended Approve Modal for Upcoming Time oFF --}}
                                        @if ($upcomingRequest->status == 'pending')
                                            @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                                            <a class="btn btn-default btnDeny" data-toggle="modal" title="Deny"
                                               data-target="#confirm-delete{{ $upcomingRequest->id }}" data-original-title="del">
                                                <i class="fas fa-times"></i> </a>
                                            @endif
                                        @endif
                                        {{--TODO: Delete Modal for Upcoming Time oFF --}}

                                        <div class="modal fade" id="confirm-delete{{ $upcomingRequest->id }}" tabindex="-1"
                                             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form
                                                            action="@if(isset($locale)){{route('timeoff.deny-request', [$locale, $employee->id, $upcomingRequest->id])}} @else {{route('timeoff.deny-request', ['en', $employee->id, $upcomingRequest->id])}} @endif"
                                                            method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <h5>Decline Time Off Request</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <h5>{{__('language.Are you sure you want to delete this Time Off Type?')}}
                                                            </h5>
                                                            <div class="form-group">
                                                                <label>Comment (Optional):</label>
                                                                <textarea class="form-control " id="add-comment" name="comment" cols="" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-warning waves-effect"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-danger waves-effect waves-float waves-light btn-cancel">{{__('language.Decline')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Ended Delete Modal for Upcoming Time oFF --}}
                                        @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || (isset($permissions['timeofftype'][$employee->id]['timeofftype '.$upcomingRequest->assignTimeOff->timeOffType->id]) && $permissions['timeofftype'][$employee->id]['timeofftype '.$upcomingRequest->assignTimeOff->timeOffType->id] == "edit") || Auth::user()->id == $employee->id && $upcomingRequest->status == 'pending')                                   
                                            <Button class="btn btn-default btnEdit" requestID="{{$upcomingRequest->id}}"
                                                    onclick="checkBalanceinEditRequest({{$upcomingRequest->assignTimeOff->id}})"
                                                    val="{{$upcomingRequest->assignTimeOff->timeOffType->id}}"
                                                    each-note="{{$upcomingRequest->note}}" date-start="{{$upcomingRequest->to}}"
                                                    date-end="{{$upcomingRequest->from}}" requestTimeOffDetail= "{{$upcomingRequest->requestTimeOffDetail}}" id="btn-edit" title="Edit" data-toggle="modal"
                                                    data-target="#edit-detail">
                                                <i class="fas fa-pencil-alt"></i>
                                            </Button>
                                        @endif
                                    </div>
                                </div>
                                <hr>

                            </div>
                        </div>
                        {{--TODO: Edit Modal for Upcoming Time oFF --}}
                        <div class="modal modal-slide-in fade" id="edit-detail" tabindex="-1" role="dialog" aria-labelledby="edit2" aria-hidden="true">
                            <div class="modal-dialog" style="width:500px;">
                                <div class="modal-content pt-0">
                                    <form action=" @if(isset($locale)){{route('timeoff.update-time-off', [$locale, $employee->id])}} @else {{route('timeoff.update-time-off', ['en', $employee->id])}} @endif" method="post">
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                            <h4 class="modal-title">{{__('language.Edit Time Off Request')}}</h4>
                                        </div>
                                        <div class="modal-body pt-1">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <!-- Date range -->
                                                    <div class="form-group">
                                                        <label class="control-label">Date range:</label>
                                                        <div class="input-group date-range editdate-range">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="far fa-calendar-alt"></i>
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control" id="edit-reservation" requestID="@if(isset($upcomingRequest->id)) {{$upcomingRequest->id}} @endif">
                                                            <input type="hidden" id="employeeID" value="@if(isset($upcomingRequest->employee_id)) {{$upcomingRequest->employee_id}} @endif">
                                                        </div>
                                                        <!-- /.Date range -->
                                                    </div>
                                                    <!-- /.form group -->
                                                </div>
                                                <!--------Edit Time Off Type------>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="control-label">Time Off Type</label>
                                                        <select class="form-control" id="edit_type_dropdown" name="assign_timeoff_type_id"> 
                                                            @foreach ($time_off_types as $type)
                                                                <option value="{{$type->id}}">
                                                                    {{$type->timeOffType->time_off_type_name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--------/.Edit Time Off Type------>
                                            </div>
                                            <div class="row totEdit" id="totEdit"></div>

                                            <div class="row ">
                                                <!--------Edit Message Heading------>
                                                <div class="col-md-12">
                                                    <label class="control-label"><b>Message:</b></label>
                                                </div>
                                                <div class="col-md-12">
                                                <textarea class="form-control " id="edit-timeoff-note" name="noteEdit" id="" cols="" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button class="btn btn-danger" type="button" onclick="$('#cancelRequestForm').submit();">Cancel Request</button>
                                            <div>
                                                <button type="button" class="btn btn-outline-warning waves-effect " data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                @if(Auth::user()->isAdmin() || (isset($permissions['timeofftype'][$employee->id]['timeofftype '.$upcomingRequest->assignTimeOff->timeOffType->id]) && $permissions['timeofftype'][$employee->id]['timeofftype '.$upcomingRequest->assignTimeOff->timeOffType->id] == "edit") || Auth::user()->id == $employee->id)
                                                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light btn-send">{{__('language.Update')}}</button>
                                                @else
                                                    <button type="submit" class="btn btn-primary waves-effect waves-float waves-light btn-send">{{__('language.Send')}}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                    <form id="cancelRequestForm" action="@if(isset($locale)){{route('timeoff.cancel-request', [$locale, $employee->id])}} @else {{route('timeoff.cancel-request', ['en', $employee->id])}} @endif" method="post">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-sm-12">
                            <h6 class="text-center">No Upcoming Time Off</h6>
                            <hr>
                        </div>
                    </div>
                @endif

                <br>
                <div class="row ">
                    <div class="col-sm-12">
                        <div class="d-inline-flex text-warning"><i class="fas fa-calendar-alt"></i></div>
                        <h5 class="text-warning d-inline-flex"><b> Outstanding Pending Time Off</b></h5>
                        <hr>
                    </div>
                </div>
                @if(count($pendingRequests) > 0)
                    @foreach ($pendingRequests as $pendingRequest)
                        <div class="row">
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{\Carbon\Carbon::parse($pendingRequest->to)->format('d-m-Y')}} to {{\Carbon\Carbon::parse($pendingRequest->from)->format('d-m-Y')}}
                                        <div class="">
                                <span id="sum{{$pendingRequest->id}}">
                                    {{ $requestHours[$pendingRequest->id] = $pendingRequest->requestTimeOffDetail->sum('hours')}}
                                </span>
                                            hours of
                                            <span class="">
                                    {{$pendingRequest->assignTimeOff->timeOffType->time_off_type_name}}
                                </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4" edit="{{$pendingRequest->note}}">
                                        {{$pendingRequest->note}}
                                    </div>
                                    <div class="col-md-4 text-right">
                                        @if ($pendingRequest->status != 'approved')
                                            @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                                                <a class="btn btn-default btnApprove" data-toggle="modal" title="Approve"
                                                   data-target="#approve{{ $pendingRequest->id }}" data-original-title="approve">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            @endif
                                        @endif

                                        {{--FIXME: Approve Modal for Upcoming Time oFF --}}
                                        <div class="modal fade" id="approve{{ $pendingRequest->id }}" tabindex="-1"
                                             role="dialog" aria-labelledby="Approve Request" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="@if(isset($locale)){{route('timeoff.approve-request', [$locale, $employee->id, $pendingRequest->id])}} @else {{route('timeoff.approve-request', ['en', $employee->id, $pendingRequest->id])}} @endif" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <h5>Approve Time Off Request</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <h5>{{__('language.Are you sure you want to Approve this Time Off Type?')}}
                                                            </h5>
                                                            <div class="form-group">
                                                                <label>Add Comment (Optional):</label>
                                                                <textarea class="form-control " id="add-comment" name="comment" cols="" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-warning waves-effect"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-float waves-light">{{__('language.Approve')}}</button>
                                                            <input id="requesttimeoffid" type="hidden" name="requesttimeoffid"
                                                                   value="{{$pendingRequest->id}}">
                                                            <input id="commentedBy" type="hidden" name="commentedById"
                                                                   value="{{auth()->user()->id}}">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Ended Approve Modal for Upcoming Time oFF --}}
                                        @if ($pendingRequest->status == 'pending')
                                            @if(Auth::user()->isAdmin() || isset($permissions['timeofftype']['all']) && array_intersect(['manage employees PTO'], $permissions['timeofftype']['all']) || isset($permissions['timeofftype'][$employee->id]['request time off decision']))
                                            <a class="btn btn-default btnDeny" data-toggle="modal" title="Deny"
                                               data-target="#confirm-delete{{ $pendingRequest->id }}" data-original-title="del">
                                                <i class="fas fa-times"></i> </a>
                                            @endif
                                        @endif
                                        {{--TODO: Delete Modal for Upcoming Time oFF --}}

                                        <div class="modal fade" id="confirm-delete{{ $pendingRequest->id }}" tabindex="-1"
                                             role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form
                                                            action="@if(isset($locale)){{route('timeoff.deny-request', [$locale, $employee->id, $pendingRequest->id])}} @else {{route('timeoff.deny-request', ['en', $employee->id, $pendingRequest->id])}} @endif"
                                                            method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <h5>Decline Time Off Request</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <h5>{{__('language.Are you sure you want to delete this Time Off Type?')}}
                                                            </h5>
                                                            <div class="form-group">
                                                                <label>Comment (Optional):</label>
                                                                <textarea class="form-control " id="add-comment" name="comment" cols="" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-warning waves-effect"
                                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                            <button type="submit"
                                                                    class="btn btn-danger waves-effect waves-float waves-light btn-cancel">{{__('language.Decline')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Ended Delete Modal for Upcoming Time oFF --}}
                                    </div>
                                </div>
                                <hr>

                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-sm-12">
                            <h6 class="text-center">No Outstanding Pending Time Off</h6>
                            <hr>
                        </div>
                    </div>
                @endif

                <div class="row ">
                    <div class="col-sm-12  ">
                        <div class="d-inline-flex text-primary"><i class="fas fa-history"></i></div>
                        <h5 class="text-primary d-inline-flex"><b> History</b></h5>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group dropdown col-md-4">
                        <label class="control-label">Policy Type</label>
                        <select class="form-control" id="filter-typeid" name="filter-typeid">
                            <option value="">{{__('language.Select')}} {{__('language.Policy')}} {{__('language.Type')}}</option>
                            @foreach ($time_off_types as $type)
                                @if(Auth::user()->isAdmin() || isset($permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id]) && $permissions['timeofftype'][$employee->id]['timeofftype '.$type->type_id] != "request")
                                    <option value="{{$type->id}}"> {{$type->timeOffType->time_off_type_name}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group dropdown col-md-4">
                        <label class="control-label">History Year</label>
                        <select class="form-control" id="filter-year" name="filter-year">
                            <option value="">{{__('language.Select')}} {{__('language.Year')}}</option>

                        </select>
                    </div>
                    <div class="form-group dropdown col-md-4">
                        <label class="control-label">History Type</label>
                        <select class="form-control" id="earnedandrequestfilter" name="earnedandrequestfilter">
                            <option value="Earned/Used">Earned/Used</option>
                            <option value="Requests">Requests</option>
                        </select>
                    </div>
                </div>
                <br>
                <!-- /.card-header -->
                <div class="card-datatable table-responsive" id="earned_history" style="display: none;">
                    <table id="earned_history_table" class="table dataTable dtr-column">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Used (-)</th>
                                <th>Accrued (+)</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="card-datatable table-responsive" id="request_history" style="display: none;">
                    <table id="request_history_table" class="table dataTable dtr-column">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!--end: Datatable -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>



<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

<script>
    function validateDateRange(){
        $('#requestTimeOffForm').submit(function(e) {
            if (document.getElementById('to') === null || document.getElementById('from') === null) {
                e.preventDefault();
              $('#reservation').after('<span class="error DateError">Date Range is Required.</span>');
              $('.btn-ok').prop('disabled', true);
            }
            else{
                $("#reasrequestTimeOffFormonForLeaving").submit();
            }
          });
        
    }
    //For changing accrual option
    function changeAccrualOption(assignId,timeOfTypeId,policyId){
        $('#preview-tbody tr').remove();
        $('#assigntimeoftypeid').val(assignId);
        $('#timeOfTypeId').val(timeOfTypeId);
        $('#policyId').val(policyId);
        $.ajax({
            type: "get",
            url: "{{ route('timeoff.policies-type', [$locale, $employee->id]) }}",
            data:{
                'policyid' :  policyId,
                'assigntimeoftypeid' :  assignId,
                'typeId': timeOfTypeId,
            },
            success: function (data) {                        
                populateType(data,timeOfTypeId);
            }
        });
    }
    function populateType(data,timeOfTypeId) {
        $('#accrualoptions').empty();
        $('#accrual-options').modal('show');
            console.log(data['getPolicies']);
            console.log(data['attachedPolicy'].attached_policy_id)
        $.each(data['getPolicies'], function (index, value) {
            if (data['attachedPolicy'].attached_policy_id == value.id) {
                $('#accrualoptions').append(
                    "<option value='"+value.id+"' selected>"+value.policy_name+"</option>"
                );
            }
            if (data['attachedPolicy'].attached_policy_id != value.id) {
                $('#accrualoptions').append(
                    "<option value='"+value.id+"'>"+value.policy_name+"</option>"
                );
            }
            if (value.policy_name == 'None' || value.policy_name == 'Manual') {
                $("#accrualOptionPreviewBtnRow").hide();
            } else {
                $("#accrualOptionPreviewBtnRow").show();
            }
        });
    }

    //Fixed: For Adjust Balance
    function getcurrentBalance(data){
        var policy_id = data.attached_policy_id;
        var type_id = data.type_id;

        $('#pol_id').val(policy_id);
        $('#type_id').val(type_id);
        // $('#adjust-balance').modal('show');

        var v=document.getElementById("available-hours".concat(data.id)).innerHTML;
        $("#current-balance").val(v.trim());
        $("#adjust-hours").on('keyup',function(){
            var adjustHours= $("#adjust-hours").val();
            $("#addorsubtract-balance").val(adjustHours);
            //Calculating new balance
            if($("#amount-options option:selected").text() == "Add"){
                var curr= +$('#current-balance').val();
                var adding = +$("#addorsubtract-balance").val();
                var newBalance = curr + adding;
                $('#new-balance').val(newBalance);
            }
            else{
                var newBalance = $('#current-balance').val() - $("#addorsubtract-balance").val();
                $('#new-balance').val(newBalance);
            }

        });
        var optionselect= $("#amount-options option:selected").text();
        if(optionselect=="Add"){
            $("#addorsubtract-text").text(optionselect).addClass("text-success").removeClass("text-danger");
        }
        else if(optionselect=="Subtract"){
            $("#addorsubtract-text").text(optionselect).addClass("text-danger").removeClass("text-success");
        }
        $("#addorsubtract-text").text(optionselect);
    }
    //For dropdown add or subtract change
    $("#amount-options").click(function() {
        var optionselect= $("#amount-options option:selected").text();
        if(optionselect=="Add"){
            $("#addorsubtract-text").text(optionselect).addClass("text-success").removeClass("text-danger");
        }
        else if(optionselect=="Subtract"){
            $("#addorsubtract-text").text(optionselect).addClass("text-danger").removeClass("text-success");
        }
    });
    $('#adjust-balance').on("hidden.bs.modal", function(){
        $(this).find('form').trigger('reset');
    });
    //For Adjust Balance End

    //hours available
    function getBalance(bal){
        var v=document.getElementById("available-hours".concat(bal)).innerHTML;
        if(v<=0)
        {
            $("#available-hours"+bal).addClass("text-danger");
        }
        else {
            $("#available-hours"+bal).removeClass("text-danger");
        }
    }

    function limitExceed(input)
    {
        // var x=document.getElementsByClassName("daily-amount");
        var x=document.getElementById("timeOff".concat(input));
        if (x.value > 24 ){
            $('.limit-exceeds').remove();
            $("#timeOff" + input).after(
                '<span class="limit-exceeds text-danger">Value exceeds limit 24</span>'
            );
            $('.daily-amount').prop('disabled',true);
            $('.btn-ok').prop('disabled', true);
            $("#timeOff" + input).prop('disabled',false);
        }else if(x.value < 0){
            $('.limit-exceeds').remove();
            $("#timeOff" + input).after(
                '<span class="limit-exceeds text-danger">Must be greater than 0</span>'
            );
            $('.daily-amount').prop('disabled',true);
            $('.btn-ok').prop('disabled', true);
            $("#timeOff" + input).prop('disabled',false);
        }
        else{
            $('.daily-amount').prop('disabled',false);
            $('.limit-exceeds').remove();
            $('.btn-ok').prop('disabled', false);
        }
    }

    function calculate(data, date){
        if(data){ 
            var dataSet = [];
            $.each(data, function(index, val)
            {
                dataSet[index] = [val.accrual_date, val.action, val.balance, val.hours_accrued];
            });

            if ($.fn.DataTable.isDataTable( '.calculate_time_off_table' ) ) {
                $('.calculate_time_off_table').DataTable().destroy();
                $('.calculate_time_off_table').DataTable( {
                    data: dataSet,
                    columns: [
                        { title: "Accrual Date" },
                        { title: "Action" },
                        { title: "Balance" },
                        { title: "Hours Accrued" }
                    ]
                });
            }
            else
            {
                $('.calculate_time_off_table').DataTable( {
                    data: dataSet,
                    columns: [
                        { title: "Accrual Date" },
                        { title: "Action" },
                        { title: "Balance" },
                        { title: "Hours Accrued" }
                    ]
                });
            }
        }
    }
    var lastDate;
    function calculateTimeOff(data,timeoftype,policy){
        lastDate={!!$employee->joining_date!!}
        $('#calculate-time-off-tbody tr').remove();
        $('#date').val(moment().format('YYYY-MM-DD'));
        $('#date').attr('time-off-type',data.id);
         $('#date').attr('time-off-type-id',timeoftype);
         $('#date').attr('policy',policy);

        var timeOffTransactions = {!! $timeOffTransactions !!}
        $('#calculate-time-off').modal('show');
        calculate(timeOffTransactions[0][data.id], moment().format('YYYY-MM-DD'));
    }
    //Date range picker
    $('#time-off').on("hidden.bs.modal", function(){

        $('.datecheck').remove(); // for date range
        $('.btn-ok').prop('disabled', false);
        $(this).find('form').trigger('reset');
        $('#reservation').empty();
        $('#reservation').data('daterangepicker').setStartDate(moment());
        $('#reservation').data('daterangepicker').setEndDate(moment());
        $('#amountTotal').remove();
    });
    $('.datecheck').remove();
    $('#reservation').daterangepicker().on('apply.daterangepicker', function (ev, picker) {
        $('#amountTotal').remove();
        $('.DateError').addClass("hidden");
        $('#tot').after(
            '<div class="row" id="amountTotal">\n'+
            '   <div class="col-md-12">\n'+
            '        <label>Amount</label>  \n'+
            '        <div class="container card border" style="padding:0 0 0 0">\n'+
            '            <div class="card-body" style="overflow-y: auto; height:150px;" id="card-box"></div>\n'+
            '            <div class="card-footer text-muted border" id="card-footer"> \n'+
            '            </div>\n'+
            '       </div>\n'+
            '    </div>\n'+
            '</div>'
        );
        $('#card-box').empty();
        $('.totalHours').remove();
        var start = moment(picker.startDate.format('YYYY-MM-DD'));
        var end = moment(picker.endDate.format('YYYY-MM-DD'));
        var days = (end - start) / (1000 * 60 * 60 * 24);
        $('.date-range').append(
            '<input type="hidden" id="to" name="to" value="'+picker.startDate.format('YYYY-MM-DD')+'">\n'+
            '<input type="hidden" id="from" name="from" value="'+picker.endDate.format('YYYY-MM-DD')+'">'

        );
        // var diff = start.diff(end, 'days'); // returns correct number
        var dates = [];
        do {
            dates.push(start.clone().toDate());
        } while (start.add(1, 'days').diff(end) <= 0);
        var days = [];
        $.each(dates, function (index, value) {
            days.push(value.getDay());
        });
        $.each( dates , function( key, value ) {
            var index = key;
            var fullDate = value;
            const weekday= ['Sun','Mon','Tue','wed','Thu','Fri','Sat'];
            const shortName= ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            var timeOff = [ 0 , 8 , 8 , 8 , 8 , 8 , 0 ];
            var dayName = weekday[fullDate.getDay()];
            var monthName = shortName[fullDate.getMonth()];
            var month = fullDate.getMonth()+1;
            var date = fullDate.getDate() ;
            var year = fullDate.getFullYear();
            //Checks on Date rangeTODO:
            var gethrs= {!!$getHours!!};
            $.each(gethrs, function (index, values) {
                if((values.request_time_off.status == "approved" || values.request_time_off.status == "pending") && moment(picker.startDate.format('YYYY-MM-DD')).isSameOrBefore( moment(values.date)) && moment(picker.endDate.format('YYYY-MM-DD')).isSameOrAfter( moment(values.date)))
                {
                    $('.datecheck').remove();
                    $('#tot').before(
                        '\n<p class="datecheck text-danger">You’ve already requested time for this period.</p>'
                    );
                    $('.btn-ok').prop('disabled', true);
                    return false;
                }
                else{
                    $('.datecheck').remove();
                    $('.btn-ok').prop('disabled', false);
                }

            });
            //Checks on Date range ends
            $('#card-box').append(
                '<ul class="list-unstyled">\n' +
                '<li>\n' +
                '<div class="row">\n'+
                '<span class="col-md-5" style="padding-top: 8px;">'+dayName+','+monthName+' '+date+'</span>\n' +
                '<input class="form-control col-md-2 daily-amount" type="text" onkeyup="limitExceed('+index+')" id="timeOff'+index+'" value="'+timeOff[fullDate.getDay()]+'" name="dailyAmount['+year+'-'+month+'-'+date+']">\n' +
                '<span id="hrs" style="padding-top: 8px;">&nbsp; hours&nbsp;&nbsp;</span>\n' +
                '</div>\n'+
                '</li>\n' +
                '</ul>'
            );
        });//end key value foreach
        var dailySum = 0;

        $(".daily-amount").each(function(){
            dailySum += +$(this).val();
        });
        $('#card-footer').append(
            '<div class="totalHours">\n'+
            'Total :\n'+
            '<span class="totalAmount">'+dailySum+'</span>\n'+
            '<span class="totalUnit">hours</span>\n'+
            '</div>'
        );

        $(document).on("keyup", ".daily-amount", function() {
            $('.totalHours').remove();
            dailySum=0
            $(".daily-amount").each(function(){
                dailySum += +$(this).val();
            });
            $('#card-footer').append(
                '<div class="totalHours">\n'+
                'Total :\n'+
                '<span class="totalAmount">'+dailySum+'</span>\n'+
                '<span class="totalUnit">hours</span>\n'+
                '</div>'
            );
        });
    });
    $(document).on("click", "#type-value",function () {
        var value = $(this).attr('value');
        $('select[id="typeoff_type_dropdown"]').find('option[value='+value+']').prop("selected",true);
    });

    $('#date').on('change', function () {
        var value = this.value;
        var assigntimeoff=$(this).attr('time-off-type');
        var timeOfTypeId=$(this).attr('time-off-type-id');
        var policy=$(this).attr('policy');
        timeOffTransactions = {!! $timeOffTransactions !!}
        var dbLastAccrual = timeOffTransactions[0][assigntimeoff];
        if(moment(value).isSameOrBefore(dbLastAccrual.accrual_date)  ) {
            $('#calculate-time-off-tbody tr').remove();
            timeOffTransactions = {!! $timeOffTransactions !!}
            calculate(timeOffTransactions[0][assigntimeoff], value);
        }
        else{
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "get",
                url: "{{ route('timeoff.calculate', [$locale, $employee->id]) }}",
                data: {
                    'newDate': value,
                    'timeOfTypeId': timeOfTypeId,
                    'policyId': policy
                },
                cache: false,
                success: function (data) {
                    $('#calculate-time-off-tbody').empty();
                    calculate(data, value);
                }
            });
        }
    });
    
    
    var edit_reservation = $('#edit-reservation').daterangepicker();
    edit_reservation.on('apply.daterangepicker', function (ev, picker) {
        $('#editamountTotal').remove();
        $('#totEdit').after(
            '<div class="row" id="editamountTotal">\n'+
            '   <div class="col-md-12">\n'+
            '        <label>Amount</label>  \n'+   
            '        <div class="container card border" style="padding:0 0 0 0">\n'+
            '            <div class="card-body amountCard" style="overflow-y: auto; height:150px;" id="amountCard"></div>\n'+
            '            <div class="card-footer text-muted border" id="card-footer"> \n'+
            '            </div>\n'+
            '       </div>\n'+
            '    </div>\n'+
            '</div>'
        );

        $('.edittotalHours').remove();
        explode = $('#edit-reservation').val().split(' - ');

        
        var employeeID = $('#employeeID').val();
        var getId = $(this).attr('requestID');
        start_date = explode[0];
        end_date = explode[1];
        start_date = start_date.replace("/", "-");
        end_date = end_date.replace("/", "-");
        start_date = start_date.replace("/", "-");
        end_date = end_date.replace("/", "-");
        start_date = moment(picker.startDate.format('YYYY-MM-DD'));
        end_date = moment(picker.endDate.format('YYYY-MM-DD'));
        start_date = start_date['_i'];
        end_date = end_date['_i'];
        var start = moment(start_date);
        var end = moment(end_date);
        var days = (end - start) / (1000 * 60 * 60 * 24);
        $("#requestid").val(getId);
        $("#to").val(start_date);
        $("#from").val(end_date);

        var dates = [];
        do {
            dates.push(start.clone().toDate());
        } while (start.add(1, 'days').diff(end) <= 0);
        var days = [];
        $.each(dates, function (index, value) {
            days.push(value.getDay());
        });
        $.each( dates , function( key, value ) {
            var index = key;
            var fullDate = value;
            var weekday= ['Sun','Mon','Tue','wed','Thu','Fri','Sat'];
            var monthName= ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            var timeOff = [ 0 , 8 , 8 , 8 , 8 , 8 , 0 ];
            var dayName = weekday[fullDate.getDay()];
            var monthName = monthName[fullDate.getMonth()];
            var month = fullDate.getMonth()+1;
            var date = fullDate.getDate() ;
            var year = fullDate.getFullYear();

            var gethrs= {!!$getHours!!};
            $.each(gethrs, function (index, values) {
                if( (moment(picker.startDate.format('YYYY-MM-DD')).isSameOrBefore( values.date) &&
                    moment(picker.endDate.format('YYYY-MM-DD')).isSameOrAfter( values.date) ) && values.request_time_off.employee_id == employeeID && (values.request_time_off.status == "approved" || values.request_time_off.status == "pending"))
                {
                    $('.datecheck').remove();
                    $('#totEdit').before(
                        '\n<p class="datecheck text-danger">You’ve already requested time for this period.</p>'
                    );
                    $('.btn-send').prop('disabled', true);
                    return false;
                }
                else{
                    $('.datecheck').remove();
                    $('.btn-send').prop('disabled', false);
                }

            });

            $('.amountCard').append(
                '<ul class="list-unstyled">\n' +
                '<li>\n' +
                '<div class="row">\n'+
                '<span class="col-md-3" style="padding-top: 8px;">'+dayName+','+monthName+' '+date+'</span>\n' +
                '<input class="form-control col-md-2 editdaily-amount" type="text" onkeyup="checkEditRequestExceedsLimit('+index+')" id="edittimeOff'+index+'" value="'+timeOff[fullDate.getDay()]+'" name="dailyAmount['+year+'-'+month+'-'+date+']">\n' +
                '<span style="padding-top: 8px;">&nbsp;hours</span>\n' +
                '</div>\n'+
                '</li>\n' +
                '</ul>'
            );
        });//end key value foreach
        var dailySum = 0;
        $(".editdaily-amount").each(function(){
            dailySum += +$(this).val();
        });
        $('#card-footer').append(
            '<div class="totalHours">\n'+
            'Total :\n'+
            '<span class="totalAmount">'+dailySum+'</span>\n'+
            '<span class="totalUnit">hours</span>\n'+
            '</div>'
        );

        $(document).on("keyup", ".editdaily-amount", function() {
            $('.totalHours').remove();
            dailySum=0;
            $(".editdaily-amount").each(function(){
                dailySum += +$(this).val();
            });
            $('#card-footer').append(
                '<div class="totalHours">\n'+
                'Total :\n'+
                '<span class="totalAmount">'+dailySum+'</span>\n'+
                '<span class="totalUnit">hours</span>\n'+
                '</div>'
            );
        });            
    });
    //////////////////////////////////////////Upcoming time off view Edit////////////////////////////////////
    $(document).ready(function () {
        //for checked dropdown TypeOff
        $(document).on("click", ".btnEdit",function () {
            var value = $(this).attr('val');
            $('select[id="edit_type_dropdown"]').find('option[value='+value+']').prop("selected",true);
        });
        //End checked dropdown TypeOff
        $('.btnEdit').on('click',function () {
            var start = $(this).attr('date-start');
            var end = $(this).attr('date-end');
            var getId = $(this).attr('requestid');
            var requestTimeOffDetails = $(this).attr('requestTimeOffDetail');

            start_date = start.split('-');
            end_date = end.split('-');
            edit_reservation.data('daterangepicker').setStartDate(start_date[1] + '/' + start_date[2] + '/' + start_date[0]);
            edit_reservation.data('daterangepicker').setEndDate(end_date[1] + '/' + end_date[2] + '/' + end_date[0]);

            //for edit message
            var msg= $(this).attr('each-note');
            $('#edit-timeoff-note').text(msg);

            $('#amountCard').empty();
            $('#editamountTotal').remove();
            $('#totEdit').after(
                '<div class="row" id="editamountTotal">\n'+
                '   <div class="col-md-12">\n'+
                '        <label>Amount</label>  \n'+   
                '        <div class="container card border" style="padding:0 0 0 0">\n'+
                '            <div class="card-body amountCard" style="overflow-y: auto; height:150px;" id="amountCard"></div>\n'+
                '            <div class="card-footer text-muted border" id="card-footer"> \n'+
                '            </div>\n'+
                '       </div>\n'+
                '    </div>\n'+
                '</div>'
            );
            /////////////////////////////////////////////////show hours
            // $('#edit-reservation').on('show.daterangepicker', function(ev, picker) {
            $("#cancelRequestForm").append(
                '<input type="hidden" name="requestTimeOffId" value="'+getId+'">'
            );

            $('#amountCard').empty();
            $('.edittotalHours').remove();
            var start = moment(start);
            var end = moment(end);
            var days = (end - start) / (1000 * 60 * 60 * 24);
            $('.editdate-range').append(
                '<input type="hidden" name="requestid" id="requestid" value="'+getId+'">\n'+
                '<input type="hidden" name="to" id="to" value="'+$(this).attr('date-start')+'">\n'+
                '<input type="hidden" name="from" id="from" value="'+$(this).attr('date-end')+'">'

            );
            var dates = [];
            do {
                dates.push(start.clone().toDate());
            } while (start.add(1, 'days').diff(end) <= 0);
            var days = [];
            $.each(dates, function (index, value) {
                days.push(value.getDay());
            });

            const requestTimeOffDetail = JSON.parse(requestTimeOffDetails);
            $.each(requestTimeOffDetail, function (key, value) {
                if(getId == value.request_time_off_id)
                {
                    var index = key;
                    var fullDate =new Date(value.date);
                    var weekday= ['Sun','Mon','Tue','wed','Thu','Fri','Sat'];
                    var monthName= ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var timeOff = [ 0 , 8 , 8 , 8 , 8 , 8 , 0 ];
                    var dayName = weekday[fullDate.getDay()];
                    var monthName = monthName[fullDate.getMonth()];
                    var month = fullDate.getMonth()+1;
                    var date = fullDate.getDate() ;
                    var year = fullDate.getFullYear();

                    $('.amountCard').append(
                        '<ul class="list-unstyled">\n' +
                        '<li>\n' +
                        '<div class="row">\n'+
                        '<span class="col-md-3" style="padding-top: 8px;">'+dayName+','+monthName+' '+date+'</span>\n' +
                        '<input class="form-control col-md-2 editdaily-amount" type="text" onkeyup="checkEditRequestExceedsLimit('+index+')" id="edittimeOff'+index+'" value="'+value.hours+'" name="dailyAmount['+year+'-'+month+'-'+date+']">\n' +
                        '<span style="padding-top: 8px;">&nbsp;hours</span>\n' +
                        '</div>\n'+
                        '</li>\n' +
                        '</ul>'
                    );
                }
                
            });//end key value foreach
            var dailySum = 0;

            $(".editdaily-amount").each(function(){
                dailySum += +$(this).val();
            });
            $('#card-footer').append(
                '<div class="edittotalHours">\n'+
                'Total :\n'+
                '<span class="totalAmount">'+dailySum+'</span>\n'+
                '<span class="totalUnit">hours</span>\n'+
                '</div>'
            );

            $(document).on("keyup", ".editdaily-amount", function() {
                $('.edittotalHours').remove();
                dailySum=0
                $(".editdaily-amount").each(function(){
                    dailySum += +$(this).val();
                });
                $('#card-footer').append(
                    '<div class="edittotalHours">\n'+
                    'Total :\n'+
                    '<span class="totalAmount">'+dailySum+'</span>\n'+
                    '<span class="totalUnit">hours</span>\n'+
                    '</div>'
                );
            });
            ////////////////////show hours
            // });
            $('#edit-detail').on("hidden.bs.modal", function(){
                $('#card-box').empty();
                $('#card-footer').empty();
                $('#edit-timeoff-note').empty();
                $('#editamountTotal').remove();
                $('.datecheck').remove();
                $('.btn-send').prop('disabled', false);
            });
            $('#edit-reservation').on('apply.daterangepicker', function(ev, picker) {
                $('#amountCard').empty();
                $('.edittotalHours').remove();
                var start = moment(picker.startDate.format('YYYY-MM-DD'));
                var end = moment(picker.endDate.format('YYYY-MM-DD'));
                var days = (end - start) / (1000 * 60 * 60 * 24);
                $('.editdate-range').append(
                    '<input type="hidden" name="to" value="'+picker.startDate.format('YYYY-MM-DD')+'">\n'+
                    '<input type="hidden" name="from" value="'+picker.endDate.format('YYYY-MM-DD')+'">'

                );

                // var diff = start.diff(end, 'days'); // returns correct number
                var dates = [];
                do {
                    dates.push(start.clone().toDate());
                } while (start.add(1, 'days').diff(end) <= 0);
                var days = [];
                $.each(dates, function (index, value) {
                    days.push(value.getDay());
                });
                $.each( dates , function( key, value ) {
                    var index = key;
                    var fullDate = value;
                    var weekday= ['Sun','Mon','Tue','wed','Thu','Fri','Sat'];
                    var monthName= ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var timeOff = [ 0 , 8 , 8 , 8 , 8 , 8 , 0 ];
                    var dayName = weekday[fullDate.getDay()];
                    var monthName = monthName[fullDate.getMonth()];
                    var month = fullDate.getMonth()+1;
                    var date = fullDate.getDate() ;
                    var year = fullDate.getFullYear();

                    //Checks on Edit request Date range
                    var gethrs= {!!$getHours!!};

                    $.each(gethrs, function (index, values) {
                        if( (moment(picker.startDate.format('YYYY-MM-DD')).isSameOrBefore( values.date) &&
                            moment(picker.endDate.format('YYYY-MM-DD')).isSameOrAfter( values.date) ) && values.request_time_off.employee_id == employeeID && (values.request_time_off.status == "approved" || values.request_time_off.status == "pending") )
                        {
                            $('.datecheck').remove();
                            $('#totEdit').before(
                                '\n<p class="datecheck text-danger">You’ve already requested time for this period.</p>'
                            );
                            $('.btn-send').prop('disabled', true);
                            return false;
                        }
                        else{
                            $('.datecheck').remove();
                            $('.btn-send').prop('disabled', false);
                        }

                    });
                    //Checks on Edit request Date range

                    $('.amountCard').append(
                        '<ul class="list-unstyled">\n' +
                        '<li>\n' +
                        '<div class="row">\n'+
                        '<span class="col-md-3">'+dayName+','+monthName+' '+date+'</span>\n' +
                        '<input class="form-control col-md-2 editdaily-amount" onkeyup="checkEditRequestExceedsLimit('+index+')" id="edittimeOff'+index+'" type="text" value="'+timeOff[fullDate.getDay()]+'" name="dailyAmount['+year+'-'+month+'-'+date+']">\n' +
                        '<span>hours</span>\n' +
                        '</div>\n'+
                        '</li>\n' +
                        '</ul>'
                    );
                });//end key value foreach
                var dailySum = 0;

                $(".editdaily-amount").each(function(){
                    dailySum += +$(this).val();
                });
                $('#card-footer').append(
                    '<div class="edittotalHours">\n'+
                    'Total :\n'+
                    '<span class="totalAmount">'+dailySum+'</span>\n'+
                    '<span class="totalUnit">hours</span>\n'+
                    '</div>'
                );

                $(document).on("keyup", ".editdaily-amount", function() {
                    $('.edittotalHours').remove();
                    dailySum=0
                    $(".editdaily-amount").each(function(){
                        dailySum += +$(this).val();
                    });
                    $('#card-footer').append(
                        '<div class="edittotalHours">\n'+
                        'Total :\n'+
                        '<span class="totalAmount">'+dailySum+'</span>\n'+
                        '<span class="totalUnit">hours</span>\n'+
                        '</div>'
                    );
                });
            });
        });
    });

    //Checks for edit time of request
    function checkEditRequestExceedsLimit(input)
    {//if limit exceeds 24 hours then disable
        var x=document.getElementById("edittimeOff".concat(input));
        if (x.value > 24 ){
            $('.limit-exceeds').remove();
            $("#edittimeOff" + input).after(
                '<span class="limit-exceeds text-danger">Value exceeds limit 24 </span>'
            );
            $('.editdaily-amount').prop('disabled',true);
            $('.btn-send').prop('disabled', true);
            $("#edittimeOff" + input).prop('disabled',false);
        }else if(x.value < 0){
            $('.limit-exceeds').remove();
            $("#edittimeOff" + input).after(
                '<span class="limit-exceeds text-danger">Must be greater than 0</span>'
            );
            $('.editdaily-amount').prop('disabled',true);
            $('.btn-send').prop('disabled', true);
            $("#edittimeOff" + input).prop('disabled',false);
        }
        else{
            $('.editdaily-amount').prop('disabled',false);
            $('.limit-exceeds').remove();
            $('.btn-send').prop('disabled', false);
        }
    }
    //hours available alert message in edit time of request
    function checkBalanceinEditRequest(bal){
        var v=document.getElementById("available-hours".concat(bal)).innerHTML;
        if(v<=0)
        {
            $("#available-hours"+bal).addClass("text-danger");
        }
        else {
            $("#available-hours"+bal).removeClass("text-danger");
        }
    }

    $(document).ready(function(){
        $('#request_history_table').DataTable();
        $('#earned_history_table').DataTable();
        var type = $('#filter-typeid').val();
        var year = $('#filter-year').val();
        var earnedandrequestfilter = $('#earnedandrequestfilter option:selected').val();
        ajaxget(type,year,earnedandrequestfilter);

        $('#filter-typeid').change(function(){
            var type = $('#filter-typeid').val();
            var year = $('#filter-year').val();
            var earnedandrequestfilter = $('#earnedandrequestfilter option:selected').val();
            ajaxget(type,year,earnedandrequestfilter);
        });
        $('#filter-year').change(function(){
            var type = $('#filter-typeid').val();
            var year = $('#filter-year').val();
            var earnedandrequestfilter = $('#earnedandrequestfilter option:selected').val();
            // $('#history_table').DataTable().destroy();
            ajaxget(type,year,earnedandrequestfilter);
        });
        $('#earnedandrequestfilter').change(function(){
            var type = $('#filter-typeid').val();
            var year = $('#filter-year').val();
            var earnedandrequestfilter = $('#earnedandrequestfilter option:selected').val();
            ajaxget(type,year,earnedandrequestfilter);
        });

        function ajaxget(type,year,earnedandrequestfilter) {                    
            $.ajax({
                type: "get",
                url: "{{ route('timeoff.history', [$locale, $employee->id]) }}",
                data: {
                    'typeid':type,
                    'year' : year,
                    'earnedandrequestfilter' : earnedandrequestfilter
                },
                cache: false,
                success: function (data) {
                    filterTable(data);
                }
            });
        }

        function filterTable(data){
            var earnedandrequestfilterVal = $('#earnedandrequestfilter option:selected').val();
            $('#history-table-head tr').remove();
            $('#history-table-body tr').remove();

            if (earnedandrequestfilterVal == 'Requests')
            {
                var getRequestHours=0;
                var sumOfRequestHours=0;
                var getRequestHours=0;
                var sumOfRequestHours=0;
                var dataSet = [];
                $.each(data, function(index, val)
                {
                    getRequestHours = $.map(val.request_time_off_detail, function (elementOrValue, indexOrKey) {
                        return elementOrValue.hours;
                    });
                    $.each(getRequestHours, function (indexInArray, v) {
                        sumOfRequestHours += v;
                    });

                    let dateTo = new Date(val.to);
                    let dateToFormat = dateTo.getDate() + "-" + (dateTo.getMonth() + 1) + "-" + dateTo.getFullYear();
                    let dateFrom = new Date(val.from);
                    let dateFromFormat = dateFrom.getDate() + "-" + (dateFrom.getMonth() + 1) + "-" + dateFrom.getFullYear();

                    if(val.status == 'approved') {
                        dataSet[index] = [dateToFormat +" / "+ dateFromFormat , val.assign_time_off.time_off_type.time_off_type_name + '<br>'+ val.note, '<div class="badge badge-light-success">'+ val.status +'</div>', sumOfRequestHours];
                    }
                    if (val.status == 'Denied' || val.status == 'Canceled') {
                        dataSet[index] = [dateToFormat +" / "+ dateFromFormat , val.assign_time_off.time_off_type.time_off_type_name + '<br>'+ val.note, '<div class="badge badge-light-danger">'+ val.status +'</div>', sumOfRequestHours];
                    }
                    if (val.status == 'pending') {
                        dataSet[index] = [dateToFormat +" / "+ dateFromFormat , val.assign_time_off.time_off_type.time_off_type_name + '<br>'+ val.note, '<div class="badge badge-light-secondary">'+ val.status +'</div>', sumOfRequestHours];
                    }
                    getRequestHours = 0;sumOfRequestHours = 0;
                });

                document.getElementById('earned_history').style.display = "none";
                document.getElementById('request_history').style.display = "";
                $('#request_history_table').DataTable().destroy();
                $('#request_history_table').DataTable( {
                    data: dataSet
                });
            }
            else
            {
                var dataSet = [];
                $.each(data, function(index, val)
                {
                    let dateTo = new Date(val.accrual_date);
                    let formattedDate = dateTo.getDate() + "-" + (dateTo.getMonth() + 1) + "-" + dateTo.getFullYear();

                    if(val.hours_accrued < 0){
                        if(val.note == null) {
                            dataSet[index] = [formattedDate, val.assign_time_off.time_off_type.time_off_type_name, val.hours_accrued.toFixed(2), 'N/A', val.balance.toFixed(2)];
                        } else {
                            dataSet[index] = [formattedDate, val.assign_time_off.time_off_type.time_off_type_name + '<br>'+ val.note, val.hours_accrued.toFixed(2), 'N/A', val.balance.toFixed(2)];
                        }
                    } else {
                        if(val.note == null) {
                            dataSet[index] = [formattedDate, val.assign_time_off.time_off_type.time_off_type_name, 'N/A', val.hours_accrued.toFixed(2), val.balance.toFixed(2)];
                        } else {
                            dataSet[index] = [formattedDate, val.assign_time_off.time_off_type.time_off_type_name + '<br>'+ val.note, 'N/A', val.hours_accrued.toFixed(2), val.balance.toFixed(2)];
                        }
                    }
                });

                document.getElementById('request_history').style.display = "none";
                document.getElementById('earned_history').style.display = "";
                $('#earned_history_table').DataTable().destroy();
                $('#earned_history_table').DataTable( {
                    data: dataSet,
                });
            }
        }
    });

    //dropdown year filter populate to current+1 years
    var joiningdate = "{!!$employee->joining_date!!}";
    var joiningyear = moment(joiningdate,'YYYY-MM-DD').format('YYYY');
    max = moment().format('YYYY');
    select = document.getElementById('filter-year');

    for (var i = max; i>=joiningyear; i--)
    {
        var opt=document.createElement('option'); opt.value=i; opt.innerHTML=i;
        select.appendChild(opt);
    }

    $(document).ready(function () {
        $('#accrualoptions').change(function () {
            // var type = $('#assign_id').val();
            // var policy = $('#accrualoptions').val();
            // var date = $('#policyStartDate').val();
            // console.log(type,policy,date);
        });
        $('#btn-preview').on('click',function () {
            var type = $('#assigntimeoftypeid').val();
            var policy = $('#accrualoptions').val();
            var date = $('#policyStartDate').val();
            $.ajax({
            type: "get",
                url: "{{ route('timeoff.change-policy', [$locale, $employee->id])}}",
                data:{
                'timeOfTypeId': type,
                    'policyId': policy,
                    'policyStartDate': date
            },
            success: function (data) {
                populatePreview(data);
            }
        });
    });

    function populatePreview(data){
        if(data){ 
            var dataSet = [];
            $.each(data, function(index, val)
            {
                dataSet[index] = [val.accrual_date, val.action, val.balance, val.hours_accrued];
            });

            if ($.fn.DataTable.isDataTable( '.preview_table' ) ) {
                $('.preview_table').DataTable().destroy();
                $('.preview_table').DataTable( {
                    data: dataSet,
                    columns: [
                        { title: "Accrual Date" },
                        { title: "Action" },
                        { title: "Balance" },
                        { title: "Hours Accrued" }
                    ]
                });
            }
            else
            {
                $('.preview_table').DataTable( {
                    data: dataSet,
                    columns: [
                        { title: "Accrual Date" },
                        { title: "Action" },
                        { title: "Balance" },
                        { title: "Hours Accrued" }
                    ]
                });
            }
        }
    }
    });

    //accrual option modal
    $('#accrualoptions').change(function () { 
     var accrualPolicyDropdownText = $('#accrualoptions').find(":selected").text();
     if(accrualPolicyDropdownText == 'None' || accrualPolicyDropdownText == "Manual Updated Balance"){
        $("#accrualOptionPreviewBtnRow").hide();
        $("#accrualOptionPreviewTableRow").hide();
        $('#policyStartDate').removeAttr("required");
     } else{                 
        $('#policyStartDate').prop("required",true);
        $("#accrualOptionPreviewBtnRow").show();
        $("#accrualOptionPreviewTableRow").show();
     }
        
    });
</script>
@section('vendor-script')
    {{-- vendor files --}}
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
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/swiper.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-adjust-balance.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/extensions/ext-component-swiper.js')) }}"></script>
@endsection
@stop
