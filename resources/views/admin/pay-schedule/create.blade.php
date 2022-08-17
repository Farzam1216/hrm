@extends('layouts.contentLayoutMaster')
@section('title','Create Pay Schedule')

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
                <form id="pay-schedule-form" action="@if(isset($locale)) {{route('pay-schedule.store', [$locale])}} @else {{route('pay-schedule.store', ['en'])}} @endif" method="post">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="control-label">{{__('language.Name')}} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Pay Schedule')}} {{__('language.Name')}}" value="{{ old('name') }}">
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
                                        <option value="Weekly">Weekly</option>
                                        <option value="Every other week">Every other week</option>
                                        <option value="Twice a monthly">Twice a monthly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Quarterly">Quarterly</option>
                                        <option value="Twice a yearly">Twice a yearly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row frequency-type"></div>
                                
                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label">Pay days are... <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group col-md-2 col-6">
                                    <select class="form-control custom-select" name="pay_days" id="pay_days" onchange="payDates();">
                                        <option value="0" @if(old('pay_days') == '0') selected @endif>0</option>
                                        <option value="1" @if(old('pay_days') == '1') selected @endif>1</option>
                                        <option value="2" @if(old('pay_days') == '2') selected @endif>2</option>
                                        <option value="3" @if(old('pay_days') == '3') selected @endif>3</option>
                                        <option value="4" @if(old('pay_days') == '4') selected @endif>4</option>
                                        <option value="5" @if(old('pay_days') == '5') selected @endif>5</option>
                                        <option value="6" @if(old('pay_days') == '6') selected @endif>6</option>
                                        <option value="7" @if(old('pay_days') == '7') selected @endif>7</option>
                                        <option value="8" @if(old('pay_days') == '8') selected @endif>8</option>
                                        <option value="9" @if(old('pay_days') == '9') selected @endif>9</option>
                                        <option value="10" @if(old('pay_days') == '10') selected @endif>10</option>
                                        <option value="11" @if(old('pay_days') == '11') selected @endif>11</option>
                                        <option value="12" @if(old('pay_days') == '12') selected @endif>12</option>
                                        <option value="13" @if(old('pay_days') == '13') selected @endif>13</option>
                                        <option value="14" @if(old('pay_days') == '14') selected @endif>14</option>
                                        <option value="15" @if(old('pay_days') == '15') selected @endif>15</option>
                                    </select>
                                </div>
                                    <label class="pt-1">day(s) after the period ends</label>
                            </div>
                                
                            <div class="row">
                                <div class="form-group col-6">
                                    <label class="control-label">If a pay day lands on a weekend or federal holiday? <span class="text-danger">*</span></label>
                                    <select class="form-control custom-select" name="exceptional_pay_day" id="exceptional_pay_day" onchange="payDates();">
                                        <option value="Before" @if(old('exceptional_pay_day') == 'Before') selected @endif>Pay on the previous business day</option>
                                        <option value="After" @if(old('exceptional_pay_day') == 'After') selected @endif>Pay on the next business day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Add Pay Schedule"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Create')}}</span></button>
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
    <script src="{{ asset('js/scripts/pay-schedule-create-script.js') }}"></script>
@endsection